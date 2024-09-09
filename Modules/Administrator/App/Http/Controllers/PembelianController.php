<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;
use Dompdf\Dompdf;
use Modules\Administrator\App\Models\Beli;
use Modules\Administrator\App\Models\Sales;
use Modules\Administrator\App\Models\Users;
use Modules\Administrator\App\Models\Warehouse;


class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'level' => LevelMember::where('status_level', 1)->get()
        ];
        return view('administrator::pembelian/index', $data);
    }


    public function jsonPembelian(Request $req)
    {
        $response = Beli::jsonList($req);
        return response()->json($response);
    }
    public function jsonListDetailBeli(Request $req)
    {
        $response = Beli::jsonListDetailBeli($req);
        return response()->json($response);
    }

    public function jsonListDetailBeliEdit(Request $req)
    {
        $response = DB::table('tbl_trn_detail_beli')
            ->where('header_id', $req->id)
            ->select('*')
            ->get();
        return response()->json($response);
    }

    public function jsonDeleteBeli(Request $req)
    {
        DB::beginTransaction();
        try {
            DB::table("tbl_trn_detail_beli")->where('header_id', $req->id)->delete();
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

    public function getJsonPriceBeli(Request $req)
    {
        try {
            $data = DB::table('vw_master_price')
                ->where(['barcode' => $req->barcode])
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

    public function jsonSaveTransaksiBeli(Request $req)
    {
        $listBelanja = json_decode($req->listBelanja);
        $dataHeader = [
            'date_trans'        => $req->_dateTransaksi . date(' h:i:s'),
            'type'              => 'in',
            'types'             => 'beli',
            'no_transaksi'      => $req->_noTransaksi,
            'status_bayar'      => 'lunas',
            'total_bayar'       => $req->_total_bayar,
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => session()->get("user_id"),
        ];

        $detailBelanja = [];
        for ($i = 0; $i < count($listBelanja); $i++) {
            $details = array(
                'date'                  => $req->_dateTransaksi . date(' H:i:s'),
                'header_id'             => '',
                'item_id'               => $listBelanja[$i]->item_id,
                'supplier_name'         => $listBelanja[$i]->supplier,
                'item_name'             => $listBelanja[$i]->item_name,
                'unit_id'               => $listBelanja[$i]->satuan_id,
                'unit_name'             => $listBelanja[$i]->satuan,
                'kode_item'             => $listBelanja[$i]->kode_item,
                'total_harga'           => $listBelanja[$i]->total,
                'merek'                 => $listBelanja[$i]->merek,
                'hpp'                   => $listBelanja[$i]->hpp,
                'in_stock'              => $listBelanja[$i]->qty,
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
                DB::table('tbl_trn_detail_beli')->insert($detailBelanja);
            } else {
                DB::table('tbl_trn_header_trans')->where('no_transaksi', $req->_noTransaksi)->update($dataHeader);
                $headersId = $cekHeaders->first()->id;
                for ($i = 0; $i < count($detailBelanja); $i++) {
                    $detailBelanja[$i]["header_id"] = $headersId;
                }
                DB::table('tbl_trn_detail_beli')->where('header_id', $headersId)->delete();
                DB::table('tbl_trn_detail_beli')->insert($detailBelanja);
            }
            try {
                DB::commit();
                return response()->json(['success' => true, "trans" => $req->_noTransaksi, 'msg' => 'success']);
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json(['success' => false, 'msg' => $ex->getMessage()]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonNoTransaksiBeli(Request $req)
    {
        return getNoTransaksiBeli();
    }
}
