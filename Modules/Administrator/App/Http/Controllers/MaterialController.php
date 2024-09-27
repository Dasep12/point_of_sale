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
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

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

    public function jsonmultidelete(Request $req)
    {
        DB::beginTransaction();
        try {
            $sales = DB::table('tbl_trn_detail_sales')->whereIn('item_id', $req->id);
            $beli = DB::table('tbl_trn_detail_beli')->whereIn('item_id', $req->id);
            if ($sales->count() > 0 || $beli->count() > 0) {
                return response()->json(['msg' => 'Produk Sudah Ada di Transaksi,Tidak Bisa di Hapus']);
            } else {
                DB::table('tbl_mst_harga')->whereIn('material_id', $req->id)->delete();
                DB::table('tbl_mst_material')->whereIn('id', $req->id)->delete();
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Berhasil Delete']);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
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
            $spreadsheet = IOFactory::load($file->getPathname());
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


                if ($harga_pokok == null || $harga_pokok == "") {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Column Harga Pokok Empty'
                    ]);
                }

                $cekKategori = Category::where('code_categories', $jenis_item);
                if ($cekKategori->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Kategori Item ' . $jenis_item  . ' not found'
                    ]);
                }

                $kodeItem = Material::where('kode_item', $kode_item);
                if ($kodeItem->count() > 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Kode Item ' . $kode_item  . ' exist '
                    ]);
                }

                $cekSatuan = Units::where('unit_code', $satuan);
                if ($cekSatuan->count() <= 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Unit  ' . $satuan  . ' not found'
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


    public function exportMaterial(Request $req)
    {
        $sql = "SELECT a.* , b.unit_code , f.location , d.code_categories , e.NameWarehouse
                FROM tbl_mst_material a 
                left join tbl_mst_units b on b.id = a.unit_id 
                left join tbl_mst_rak f on f.id = a.location_id
                left join tbl_mst_categories d on d.id = a.categori_id
                left join tbl_mst_warehouse e on e.id = a.warehouse_id ";

        $data = DB::select($sql);
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // Set some data in the spreadsheet
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Item');
        $sheet->setCellValue('C1', 'Barcode');
        $sheet->setCellValue('D1', 'Nama Item');
        $sheet->setCellValue('E1', 'Kategori');
        $sheet->setCellValue('F1', 'Unit');
        $sheet->setCellValue('G1', 'Merek');
        $sheet->setCellValue('H1', 'Satuan Dasar');
        $sheet->setCellValue('I1', 'Konversi Satuan Dasar');
        $sheet->setCellValue('J1', 'Harga Pokok');
        $sheet->setCellValue('K1', 'Stock Minimum');
        $sheet->setCellValue('L1', 'Tipe Item');
        $sheet->setCellValue('M1', 'Menggunakan Serial');
        $sheet->setCellValue('N1', 'Rak');
        $sheet->setCellValue('O1', 'Kode Gudang');
        $sheet->setCellValue('P1', 'Keterangan');

        // Apply borders to a single cell
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Set background color for a range of cells
        $sheet->getStyle('A1:P1')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8fc03'], // Magenta background
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Example: Freeze the first row
        $sheet->freezePane('A2');
        // Auto size columns based on the content
        $this->autoSizeColumns($sheet, range('A', 'P'));

        $start = 2;
        $no = 1;

        if (count($data) > 0) {
            foreach ($data as $d) {
                $sheet->setCellValue('A' . $start, $no++);
                $sheet->setCellValue('B' . $start, $d->kode_item);
                $sheet->setCellValue('C' . $start, $d->barcode);
                $sheet->setCellValue('D' . $start, ucwords(strtoupper($d->name_item)));
                $sheet->setCellValue('E' . $start, ucwords(strtoupper($d->code_categories)));
                $sheet->setCellValue('F' . $start, ucwords(strtoupper($d->unit_code)));
                $sheet->setCellValue('G' . $start, ucwords(strtoupper($d->merek)));
                $sheet->setCellValue('H' . $start, ucwords(strtoupper($d->satuan_dasar)));
                $sheet->setCellValue('I' . $start, ucwords(strtoupper($d->konversi_satuan)));
                $sheet->setCellValue('J' . $start, ucwords(strtoupper($d->harga_pokok)));
                $sheet->setCellValue('K' . $start, ucwords(strtoupper($d->stock_minimum)));
                $sheet->setCellValue('L' . $start, ucwords(strtoupper($d->tipe_item)));
                $sheet->setCellValue('M' . $start, ucwords(strtoupper($d->serial)));
                $sheet->setCellValue('N' . $start, ucwords(strtoupper($d->location)));
                $sheet->setCellValue('O' . $start, ucwords(strtoupper($d->NameWarehouse)));
                $sheet->setCellValue('P' . $start, ucwords(strtoupper($d->remarks)));
                $start++;
            }
        } else {
            $sheet->setCellValue('A' . $start, "data not found");
            $sheet->mergeCells('A' . $start . ':P' . $start + 1);
        }

        $sheet->getStyle('A1:P' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:P' . $start)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:P' . $start - 1)->applyFromArray($styleArray);
        $sheet->getStyle('A1:P' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        if ($req->act == "xls") {
            // Save the spreadsheet to a file
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'php');
            $writer->save($tempFile);

            // Return the file as a response
            return response()->download($tempFile, 'export.xlsx')->deleteFileAfterSend(true);
        } else if ($req->act == "pdf") {
            // Write the file to a stream
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
            $writer = new Mpdf($spreadsheet);

            // Return the file as a response
            return response()->stream(
                function () use ($writer) {
                    $writer->save('php://output');
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="export.pdf"',
                ]
            );
        }
    }

    private function autoSizeColumns($sheet, array $columns)
    {
        foreach ($columns as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
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
            $spreadsheet = IOFactory::load($file->getPathname());

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

                $cekHarga = Price::where(['member_id' => $cekMember->first()->id, 'material_id' => $cekMaterial->first()->id]);
                if ($cekHarga->count() > 0) {
                    return response()->json([
                        'success' => false,
                        'msg' => 'Harga  ' . $name_item . ' ' . $cekMember->first()->name_level . ' sudah ada di database'
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


    public function barcodeGenerate(Request $req)
    {
        // $id = ["15", "14", "13", "12", "11", "10", "9", "8", "7", "6"];
        $sql = Material::whereIn('id', $req->id)
            ->select('id', 'barcode', 'name_item')
            ->get();


        $data = [
            'data' => $sql
        ];
        $pdf = PDF::loadView('administrator::material.partials.Barcode', $data);

        $pdf->setPaper('A4', 'portrait'); // 58mm width (226 pixels at 96 DPI)

        return $pdf->stream('Barcode' . 'pdf'); // Show PDF in browser
        //return response()->json($data);
    }

    public function downloadExcelFormatMaterial(Request $req)
    {
        $filePath = public_path('document/' . $req->file);

        return response()->download($filePath, $req->file, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="materials.xlsx"',
        ]);
    }
}
