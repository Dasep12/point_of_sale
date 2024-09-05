<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Category;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Location;
use Modules\Administrator\App\Models\Material;
use Modules\Administrator\App\Models\Price;
use Modules\Administrator\App\Models\Units;
use Modules\Administrator\App\Models\Warehouse;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'warehouse' => Warehouse::where('status_warehouse', 1)->get(),
            'categ'     => Category::where('status_categories', 1)->get(),
            'units'      => Units::where('status_unit', 1)->get(),
            'member'      => LevelMember::where('status_level', 1)->get(),
        ];
        return view('administrator::material/index', $data);
    }

    public function jsonMaterial(Request $req)
    {
        $response = Material::jsonList($req);
        return response()->json($response);
    }


    public function jsonCreate(Request $req)
    {
        $resp = Material::jsonCreate($req);
        return response()->json($resp);
    }

    public function jsonDetail(Request $req)
    {
        $response = Material::find($req->id);
        return response()->json($response);
    }

    public function jsonDetailPrice(Request $req)
    {
        $response = Material::jsonListPrice($req);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Material::find($req->id);
            $cust->kode_item         =  $req->kode_item;
            $cust->barcode           =  $req->barcode;
            $cust->name_item         =  $req->name_item;
            $cust->categori_id       =  $req->categori_id;
            $cust->unit_id           =  $req->unit_id;
            $cust->merek             =  $req->merek;
            $cust->satuan_dasar      =  $req->satuan_dasar;
            $cust->konversi_satuan   =  $req->konversi_satuan;
            $cust->harga_pokok       =  $req->harga_pokok;
            $cust->stock_minimum     =  $req->stock_minimum;
            $cust->tipe_item         =  $req->tipe_item;
            $cust->serial            =  $req->serial;
            $cust->location_id       =  $req->location_id;
            $cust->warehouse_id      =  $req->warehouse_id;
            $cust->remarks           =  $req->remarks;
            $cust->updated_at        = date('Y-m-d H:i:s');
            $cust->updated_by        = session()->get("user_id");
            $cust->save();
            try {
                DB::commit();
                return response()->json(['msg' => 'success']);
            } catch (Exception $ex) {
                return response()->json(['msg' => $ex->getMessage()]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonDelete(Request $req)
    {
        $resp  = Material::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonLocation(Request $req)
    {
        $data = Location::where(["warehouse_id" => $req->warehouse_id, 'status_location' => 1])->get();
        return response()->json($data);
    }

    public function uploadItemExcel(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file_upload');

        try {
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();
            $rowCount = $sheet->getHighestRow();
            $params = [];
            for ($i = 2; $i <= $rowCount; $i++) {

                // Process the sheet data here
                $kode_item = $sheet->getCell('B' . $i)->getValue();
                $barcode = $sheet->getCell('C' . $i)->getValue();
                $nama_item = $sheet->getCell('D' . $i)->getValue();
                $jenis_item = $sheet->getCell('E' . $i)->getValue();
                $satuan = $sheet->getCell('F' . $i)->getValue();
                $merek = $sheet->getCell('G' . $i)->getValue();
                $satuan_dasar = $sheet->getCell('H' . $i)->getValue();
                $konversi_satuan = $sheet->getCell('I' . $i)->getValue();
                $harga_pokok = $sheet->getCell('J' . $i)->getValue();
                $stok_minimum = $sheet->getCell('K' . $i)->getValue();
                $tipe_item = $sheet->getCell('L' . $i)->getValue();
                $serial = $sheet->getCell('M' . $i)->getValue();
                $rak = $sheet->getCell('N' . $i)->getValue();
                $code_gudang = $sheet->getCell('O' . $i)->getValue();
                $remarks = $sheet->getCell('P' . $i)->getValue();

                $cekKategori = Category::where('code_categories', $jenis_item);
                if ($cekKategori->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Jenis Item ' . $jenis_item  . ' not found'
                    ]);
                }

                $cekSatuan = Units::where('unit_code', $satuan);
                if ($cekSatuan->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Jenis Item ' . $satuan  . ' not found'
                    ]);
                }

                $cekGudang = Warehouse::where('code_gudang', $code_gudang);
                if ($cekGudang->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Gudang ' . $code_gudang  . ' not found'
                    ]);
                }

                $cekRak = Location::where('location', $rak);
                if ($cekRak->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Location ' . $rak  . ' not found'
                    ]);
                }

                $data = [
                    'kode_item'         => $kode_item,
                    'barcode'           => $barcode,
                    'name_item'         => $nama_item,
                    'categori_id'       => $cekKategori->first()->id,
                    'unit_id'           => $cekSatuan->first()->id,
                    'merek'             => $merek,
                    'satuan_dasar'      => $satuan_dasar,
                    'konversi_satuan'   => $konversi_satuan,
                    'harga_pokok'       => $harga_pokok,
                    'stock_minimum'     => $stok_minimum,
                    'tipe_item'         => $tipe_item,
                    'serial'            => $serial,
                    'location_id'       => $cekRak->first()->id,
                    'warehouse_id'      => $cekGudang->first()->id,
                    'remarks'           => $remarks,
                    'status_item'       => 1,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => session()->get("user_id"),
                ];
                array_push($params, $data);
            }
            DB::beginTransaction();
            DB::table('tbl_mst_material')->insert($params);
            try {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded and processed successfully',
                    'data' => $params // Example data
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => $params // Example data
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
            ]);
        }
    }


    // PRICE
    public function jsonCreatePrice(Request $req)
    {
        $resp = Material::jsonCreatePrice($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonUpdatePrice(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Price::find($req->idPrice);
            $cust->member_id         =  $req->member_id;
            $cust->harga_jual        =  $req->harga_jual;
            $cust->updated_at        = date('Y-m-d H:i:s');
            $cust->updated_by        = session()->get("user_id");
            $cust->save();
            try {
                DB::commit();
                return response()->json(['msg' => 'success']);
            } catch (Exception $ex) {
                return response()->json(['msg' => $ex->getMessage()]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonDeletePrice(Request $req)
    {
        $resp  = Material::jsonDeletePrice($req);
        return response()->json(['msg' => $resp]);
    }

    public function uploadHargaExcel(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $file = $request->file('file_upload');

        try {
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());

            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();
            $rowCount = $sheet->getHighestRow();
            $params = [];
            for ($i = 2; $i <= $rowCount; $i++) {

                // Process the sheet data here
                $member = $sheet->getCell('A' . $i)->getValue();
                $kode_item = $sheet->getCell('B' . $i)->getValue();
                $name_item = $sheet->getCell('C' . $i)->getValue();
                $harga = $sheet->getCell('D' . $i)->getValue();

                $cekMember = LevelMember::where('name_level', $member);
                if ($cekMember->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Member ' . $member  . ' not found'
                    ]);
                }

                $cekMaterial = Material::where(['kode_item' => $kode_item, 'name_item' => $name_item]);
                if ($cekMaterial->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Item ' . $name_item  . ' not found'
                    ]);
                }

                $data = [
                    'member_id'         => $cekMember->first()->id,
                    'material_id'       => $cekMaterial->first()->id,
                    'harga_jual'        => $harga,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => session()->get("user_id"),
                ];
                array_push($params, $data);
            }
            DB::beginTransaction();
            DB::table('tbl_mst_harga')->insert($params);
            try {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded and processed successfully',
                    'data' => $params // Example data
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'data' => $params // Example data
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading file: ' . $e->getMessage(),
            ]);
        }
    }
}
