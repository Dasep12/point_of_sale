<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Adjust;
use Modules\Administrator\App\Models\Material;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    public function getJsonBarangAdjust(Request $req)
    {
        $data = DB::table('tbl_mst_material as a')
            ->leftJoin('tbl_mst_units as u', 'u.id', '=', 'a.unit_id')
            ->where(['barcode' => $req->barcode])
            ->select('a.*', 'u.unit_name', 'u.unit_code');
        try {
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

    public function uploadFilesAdjust(Request $request)
    {

        try {
            $request->validate([
                'files_upload' => 'required|mimes:xlsx,xls,csv',
            ]);

            $file = $request->file('files_upload');
            $filePath = $file->getPathname();

            // Start processing the file
            $spreadsheet = IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            unset($sheetData[0]);
            unset($sheetData[1]);
            $processedData = [];
            $error = [];
            foreach ($sheetData as  $row) {
                $material = Material::where('kode_item', $row['C'])->count();
                if ($material <= 0) {
                    array_push($error, ['Kode Item ' . $row['C'] . ' not found']);
                }

                $material = Material::where('name_item', $row['B'])->count();
                if ($material <= 0) {
                    array_push($error, ['Name Item ' . $row['B'] . ' not found']);
                }

                $details = DB::table('tbl_mst_material as a')
                    ->leftJoin('tbl_mst_units as b', 'b.id', '=', 'a.unit_id')
                    ->where('a.kode_item', $row['C'])
                    ->select('a.unit_id', 'b.unit_code', 'a.merek')
                    ->get()
                    ->first();


                $res = [
                    'item_name'       => $row['B'],
                    'kode_item'       => $row['C'],
                    'qty'             => $row['D'],
                    'satuan_id'       => $details->unit_id,
                    'satuan'          => $details->unit_code,
                    'merek'           => $details->merek,
                ];
                array_push($processedData, $res);
            }
            return response()->json([
                'message' => 'File processed successfully.',
                'data'    => $processedData,
                'errors'  => $error,
                'success' => count($error) > 0 ? false : true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }



    public function jsonNoTransaksiAdjust(Request $req)
    {
        return getNoTransaksiAdjust();
    }
}
