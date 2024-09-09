<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Sales;
use Modules\Administrator\App\Models\Users;
use Modules\Administrator\App\Models\Warehouse;



class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'level' => LevelMember::where('status_level', 1)->get()
        ];
        return view('administrator::sales/index', $data);
    }

    public function jsonSales(Request $req)
    {
        $response = Sales::jsonList($req);
        return response()->json($response);
    }

    public function jsonDetailSales(Request $req)
    {
        $response = Sales::jsonListDetail($req);
        return response()->json($response);
    }
    public function jsonDetailSalesEdit(Request $req)
    {
        $response = DB::table('tbl_trn_detail_sales')
            ->where('header_id', $req->id)
            ->select('*')
            ->get();
        return response()->json($response);
    }


    public function getJsonPrice(Request $req)
    {
        try {
            $sql = "SELECT * FROM vw_master_price WHERE barcode = '$req->barcode' AND member_id ='$req->member_id' ";
            $data = DB::table('vw_master_price')
                ->where(['barcode' => $req->barcode, 'member_id' => $req->member_id])
                ->select('*');

            if ($data->count() > 0) {
                $resp = ["msg" => "ok", "data" => $data->get()];
                return response()->json($resp);
            } else {
                $resp = ["msg" => "nok", "data" => "item not found"];
                return response()->json($resp);
            }
        } catch (\Exception $e) {
            $resp = ["msg" => $e->getMessage(), "data" => $data];
            return response()->json($resp);
        }
    }


    public function jsonSaveTransaksi(Request $req)
    {
        $listBelanja = json_decode($req->listBelanja);
        $dataHeader = [
            'date_trans'        => $req->_dateTransaksi . date(' h:i:s'),
            'member_id'         => $req->_member_id,
            'type'              => 'out',
            'types'             => 'sales',
            'no_transaksi'      => $req->_noTransaksi,
            'status_bayar'      => 'lunas',
            'uang_bayar'        => $req->_uang_bayar,
            'total_bayar'       => $req->_total_bayar,
            'sub_total'         => $req->_sub_total,
            'total_potongan'    => $req->_total_potongan,
            'kembalian'         => $req->_kembalian,
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => session()->get("user_id"),
            // 'updated_at'        => '',
            // 'updated_by'        => '',
        ];

        $detailBelanja = [];
        for ($i = 0; $i < count($listBelanja); $i++) {
            $details = array(
                'date'                  => $req->_dateTransaksi,
                'header_id'             => '',
                'item_id'               => $listBelanja[$i]->item_id,
                'item_name'             => $listBelanja[$i]->item_name,
                'unit_id'               => $listBelanja[$i]->satuan_id,
                'unit_name'             => $listBelanja[$i]->satuan,
                'kode_item'             => $listBelanja[$i]->kode_item,
                'merek'                 => $listBelanja[$i]->merek,
                'harga_jual'            => $listBelanja[$i]->harga_jual,
                'out_stock'             => $listBelanja[$i]->qty,
                'discount'              => $listBelanja[$i]->discount,
                'created_at'            => date('Y-m-d H:i:s'),
                'created_by'            => session()->get("user_id"),
            );
            array_push($detailBelanja, $details);
        }



        if (count($listBelanja) <= 0) {
            return response()->json(['msg' => "list belanja tidak boleh kosong"]);
        }

        DB::beginTransaction();
        try {

            $cekHeaders = DB::table("tbl_trn_header_trans")->where('no_transaksi', $req->_noTransaksi);
            $headersId = "";
            if ($cekHeaders->count() <= 0) {
                DB::table('tbl_trn_header_trans')->insert($dataHeader);
                $headersId = DB::getPdo()->lastInsertId();
                for ($i = 0; $i < count($detailBelanja); $i++) {
                    $detailBelanja[$i]["header_id"] = $headersId;
                }
                DB::table('tbl_trn_detail_sales')->insert($detailBelanja);
            } else {
                DB::table('tbl_trn_header_trans')->where('no_transaksi', $req->_noTransaksi)->update($dataHeader);
                $headersId = $cekHeaders->first()->id;
                for ($i = 0; $i < count($detailBelanja); $i++) {
                    $detailBelanja[$i]["header_id"] = $headersId;
                }
                DB::table('tbl_trn_detail_sales')->where('header_id', $headersId)->delete();
                DB::table('tbl_trn_detail_sales')->insert($detailBelanja);
            }

            try {
                DB::commit();
                return response()->json(['msg' => "success", "trans" => $req->_noTransaksi]);
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json(['msg' => $ex->getMessage()]);
            }
            dd($req->all());
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonPrintStruck(Request $req)
    {

        $header = DB::table("tbl_trn_header_trans")
            ->where('no_transaksi', $req->no_trans)->first();
        $data = [
            'store' => Warehouse::first(),
            'user'  => Users::where('id', $header->created_by)->first(),
            'header' => $header,
            'detail' => DB::table("tbl_trn_detail_sales")
                ->where('header_id', $header->id)
                ->get()
        ];

        // return view('administrator::sales.partials.struk', compact('items', 'total'));
        $pdf = PDF::loadView('administrator::sales.partials.struk', $data);

        // Set the paper size to a custom size for thermal printing
        $pdf->setPaper([0, 0, 226, 700], 'portrait'); // 58mm width (226 pixels at 96 DPI)

        return $pdf->stream('thermal-receipt.pdf'); // Show PDF in browser
        // return $pdf->download('thermal-receipt.pdf'); // Download PDF directly

    }

    public function jsonPrintInvoice(Request $req)
    {

        $header = DB::table("tbl_trn_header_trans as a")
            ->leftJoin("tbl_mst_level_member as b", 'b.id', '=', 'a.member_id')
            ->leftJoin("tbl_mst_users as c", "c.id", '=', 'a.created_by')
            ->where('a.no_transaksi', $req->no_trans)
            ->select('a.*', 'b.name_level', 'c.fullname')
            ->first();
        $data = [
            'store' => Warehouse::first(),
            'user'  => Users::where('id', $header->created_by)->first(),
            'header' => $header,
            'detail' => DB::table("tbl_trn_detail_sales")
                ->where('header_id', $header->id)
                ->get()
        ];

        $pdf = PDF::loadView('administrator::sales.partials.invoice', $data);

        $pdf->setPaper('A4', 'portrait'); // 58mm width (226 pixels at 96 DPI)

        return $pdf->stream('INVOICE-' . $req->no_trans . '.pdf'); // Show PDF in browser
        // return $pdf->download('thermal-receipt.pdf'); // Download PDF directly

    }

    public function jsonCancelTransaksi(Request $req)
    {

        DB::beginTransaction();
        try {
            $noTrans = $req->no_transaksi;
            $data = DB::table('tbl_trn_header_trans')
                ->where('no_transaksi', $noTrans);
            if ($data->count() > 0) {
                $data->update(['status_bayar' => 'cancel']);
                $msg = ['msg' => "success", 'txt' => 'pesanan di batalkan'];
            } else {
                $msg = ['msg' => "success", 'txt' => 'no transaksi tidak ditemukan'];
            }
            try {
                DB::commit();
                return response()->json($msg);
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json(['msg' => $ex->getMessage()]);
            }
        } catch (\Exception $e) {
        }
    }

    public function jsonDeleteSales(Request $req)
    {
        DB::beginTransaction();
        try {
            DB::table("tbl_trn_detail_sales")->where('header_id', $req->id)->delete();
            DB::table("tbl_trn_header_trans")->where('id', $req->id)->delete();
            try {
                DB::commit();
                return response()->json(['msg' => "success"]);
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json(['msg' =>  $ex->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonNoTransaksi(Request $req)
    {
        return getNoTransaksi();
    }
}
