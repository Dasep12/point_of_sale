<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Adjust;



class AdjustController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'level' => LevelMember::where('status_level', 1)->get()
        ];
        return view('administrator::adjust/index', $data);
    }


    public function jsonAdjust(Request $req)
    {
        $response = Adjust::jsonList($req);
        return response()->json($response);
    }

    public function jsonListDetailAdjust(Request $req)
    {
        $response = Adjust::jsonListDetailAdjust($req);
        return response()->json($response);
    }

    public function jsonDeleteAdjust(Request $req)
    {
        DB::beginTransaction();
        try {

            if ($req->type == "in") {
                $detail_table =  "tbl_trn_detail_beli";
            } else {
                $detail_table =  "tbl_trn_detail_sales";
            }
            DB::table($detail_table)->where('header_id', $req->id)->delete();
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

    public function jsonDetailAdjust(Request $req)
    {
        if ($req->type == "in") {
            $detail_table =  "tbl_trn_detail_beli";
        } else {
            $detail_table =  "tbl_trn_detail_sales";
        }
        $data = DB::table($detail_table)
            ->where('header_id', $req->id)
            ->select('*')
            ->get();
        return response()->json($data);
    }

    public function jsonSaveTransaksiAdjust(Request $req)
    {
        $listBelanja = json_decode($req->listBelanja);
        $dataHeader = [
            'date_trans'        => $req->_dateTransaksi . date(' h:i:s'),
            'type'              => $req->_type_adjust,
            'types'             => 'Adjust',
            'no_transaksi'      => $req->_noTransaksi,
            'status_bayar'      => 'lunas',
            'total_bayar'       => 0,
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => session()->get("user_id"),
        ];

        $detailBelanja = [];
        if ($req->_type_adjust == "in") {
            $detail_table =  "tbl_trn_detail_beli";
            for ($i = 0; $i < count($listBelanja); $i++) {
                $details = array(
                    'date'                  => $req->_dateTransaksi . date(' H:i:s'),
                    'header_id'             => '',
                    'item_id'               => $listBelanja[$i]->item_id,
                    'item_name'             => $listBelanja[$i]->item_name,
                    'unit_id'               => $listBelanja[$i]->satuan_id,
                    'unit_name'             => $listBelanja[$i]->satuan,
                    'kode_item'             => $listBelanja[$i]->kode_item,
                    'total_harga'           => 0,
                    'merek'                 => $listBelanja[$i]->merek,
                    'hpp'                   => 0,
                    'in_stock'              => $listBelanja[$i]->qty,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'created_by'            => session()->get("user_id"),
                );
                array_push($detailBelanja, $details);
            }
        } else {
            $detail_table =  "tbl_trn_detail_sales";
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
                    'harga_jual'            => 0,
                    'out_stock'             => $listBelanja[$i]->qty,
                    'discount'              => 0,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'created_by'            => session()->get("user_id"),
                );
                array_push($detailBelanja, $details);
            }
        }

        if (count($listBelanja) <= 0) {
            return response()->json(['success' => false, 'msg' => "list item tidak boleh kosong"]);
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
                DB::table($detail_table)->insert($detailBelanja);
            } else {
                DB::table('tbl_trn_header_trans')->where('no_transaksi', $req->_noTransaksi)->update($dataHeader);
                $headersId = $cekHeaders->first()->id;
                for ($i = 0; $i < count($detailBelanja); $i++) {
                    $detailBelanja[$i]["header_id"] = $headersId;
                }
                DB::table($detail_table)->where('header_id', $headersId)->delete();
                DB::table($detail_table)->insert($detailBelanja);
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



    public function jsonNoTransaksiAdjust(Request $req)
    {
        return getNoTransaksiAdjust();
    }
}
