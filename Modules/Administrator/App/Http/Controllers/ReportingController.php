<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Material;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

class ReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function reportOut()
    {
        return view('administrator::reporting/sales');
    }

    public function reportIn()
    {
        return view('administrator::reporting/beli');
    }

    public function jsonListItemReporting(Request $req)
    {
        $res = Material::all();
        return response()->json($res);
    }


    public function exportReportSales(Request $req)
    {
        $sql = "SELECT a.*  , b.* from tbl_trn_detail_sales a
        left join tbl_trn_header_trans b on b.id  = a.header_id 
        WHERE b.type in ('out') and  b.types in ('sales') and 
        date_format(b.date_trans,'%Y-%m-%d') 
        between '$req->startDate' AND '$req->endDate' ";



        if ($req->material_id != "*") {
            $sql .= " AND a.material_id='" . $req->material_id . "'";
        }
        $data = DB::select($sql);
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add an image to the spreadsheet
        // $drawing = new Drawing();
        // $drawing->setName('Sample Image');
        // $drawing->setDescription('Sample Image');
        // $drawing->setPath('assets/images/logo-rim.jpg'); // Path to your image file
        // $drawing->setHeight(100); // Set the height of the image
        // $drawing->setCoordinates('A1'); // Set the cell where the image should be placed
        // $sheet->mergeCells('A1:E5');
        // $drawing->setWorksheet($sheet);


        $sheet->setCellValue('A3', 'Laporan');
        $sheet->setCellValue('B3', ':');
        $sheet->setCellValue('C3', 'Penjualan');

        $sheet->setCellValue('A4', 'Periode');
        $sheet->setCellValue('B4', ':');
        $sheet->setCellValue('C4', $req->startDate . '-' . $req->endDate);

        // Set some data in the spreadsheet
        $sheet->setCellValue('A5', 'No');
        $sheet->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'No Transaksi');
        $sheet->mergeCells('B5:B6');
        $sheet->setCellValue('C5', 'Date');
        $sheet->mergeCells('C5:C6');
        $sheet->setCellValue('D5', 'Item');
        $sheet->setCellValue('D6', 'Name');
        $sheet->setCellValue('E6', 'Kode Item');
        $sheet->setCellValue('F6', 'Merek');
        $sheet->mergeCells('D5:F5');
        $sheet->setCellValue('G5', 'Detail');
        $sheet->setCellValue('G6', 'Satuan');
        $sheet->setCellValue('H6', 'Qty');
        $sheet->setCellValue('I6', 'Harga Jual');
        $sheet->setCellValue('J6', 'Discount');
        $sheet->mergeCells('G5:J5');
        $sheet->setCellValue('K5', 'Total');
        $sheet->mergeCells('K5:K6');

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
        $sheet->getStyle('A5:K6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8fc03'], // Magenta background
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Example: Freeze the first row
        $sheet->freezePane('E3');
        // Auto size columns based on the content
        $this->autoSizeColumns($sheet, range('A', 'K'));

        $start = 7;
        $no = 1;

        if (count($data) > 0) {
            foreach ($data as $d) {
                $sheet->setCellValue('A' . $start, $no++);
                $sheet->setCellValue('B' . $start, $d->no_transaksi);
                $sheet->setCellValue('C' . $start, $d->date);
                $sheet->setCellValue('D' . $start, strtoupper($d->item_name));
                $sheet->setCellValue('E' . $start, strtoupper($d->kode_item));
                $sheet->setCellValue('F' . $start, strtoupper($d->merek));
                $sheet->setCellValue('G' . $start, strtoupper($d->unit_name));
                $sheet->setCellValue('H' . $start, strtoupper($d->out_stock));
                $sheet->setCellValue('I' . $start, number_format($d->harga_jual));
                $sheet->setCellValue('J' . $start, number_format($d->discount));
                $sheet->setCellValue('K' . $start, number_format(($d->harga_jual * $d->out_stock) - $d->discount));
                $start++;
            }
        } else {
            $sheet->setCellValue('A' . $start, "data not found");
            $sheet->mergeCells('A' . $start . ':K' . $start + 1);
        }

        $sheet->getStyle('A5:K' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:K' . $start)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A5:K' . $start - 1)->applyFromArray($styleArray);
        $sheet->getStyle('A5:K' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


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

    public function exportReportBeli(Request $req)
    {
        $sql = "SELECT a.*  , b.* from tbl_trn_detail_beli a
        left join tbl_trn_header_trans b on b.id  = a.header_id 
        WHERE b.type in ('in') and  b.types in ('beli') and 
        date_format(b.date_trans,'%Y-%m-%d') 
        between '$req->startDate' AND '$req->endDate' ";



        if ($req->material_id != "*") {
            $sql .= " AND a.material_id='" . $req->material_id . "'";
        }
        $data = DB::select($sql);
        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add an image to the spreadsheet
        // $drawing = new Drawing();
        // $drawing->setName('Sample Image');
        // $drawing->setDescription('Sample Image');
        // $drawing->setPath('assets/images/logo-rim.jpg'); // Path to your image file
        // $drawing->setHeight(100); // Set the height of the image
        // $drawing->setCoordinates('A1'); // Set the cell where the image should be placed
        // $sheet->mergeCells('A1:E5');
        // $drawing->setWorksheet($sheet);


        $sheet->setCellValue('A3', 'Laporan');
        $sheet->setCellValue('B3', ':');
        $sheet->setCellValue('C3', 'Pembelian');

        $sheet->setCellValue('A4', 'Periode');
        $sheet->setCellValue('B4', ':');
        $sheet->setCellValue('C4', $req->startDate . '-' . $req->endDate);

        // Set some data in the spreadsheet
        $sheet->setCellValue('A5', 'No');
        $sheet->mergeCells('A5:A6');
        $sheet->setCellValue('B5', 'No Transaksi');
        $sheet->mergeCells('B5:B6');
        $sheet->setCellValue('C5', 'Date');
        $sheet->mergeCells('C5:C6');
        $sheet->setCellValue('D5', 'Item');
        $sheet->setCellValue('D6', 'Name');
        $sheet->setCellValue('E6', 'Kode Item');
        $sheet->setCellValue('F6', 'Merek');
        $sheet->mergeCells('D5:F5');
        $sheet->setCellValue('G5', 'Detail');
        $sheet->setCellValue('G6', 'Satuan');
        $sheet->setCellValue('H6', 'Qty');
        $sheet->setCellValue('I6', 'Harga Beli');
        $sheet->mergeCells('G5:I5');
        $sheet->setCellValue('J5', 'Total');
        $sheet->mergeCells('J5:J6');

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
        $sheet->getStyle('A5:J6')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8fc03'], // Magenta background
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Example: Freeze the first row
        $sheet->freezePane('E3');
        // Auto size columns based on the content
        $this->autoSizeColumns($sheet, range('A', 'J'));

        $start = 7;
        $no = 1;

        if (count($data) > 0) {
            foreach ($data as $d) {
                $sheet->setCellValue('A' . $start, $no++);
                $sheet->setCellValue('B' . $start, $d->no_transaksi);
                $sheet->setCellValue('C' . $start, $d->date);
                $sheet->setCellValue('D' . $start, strtoupper($d->item_name));
                $sheet->setCellValue('E' . $start, strtoupper($d->kode_item));
                $sheet->setCellValue('F' . $start, strtoupper($d->merek));
                $sheet->setCellValue('G' . $start, strtoupper($d->unit_name));
                $sheet->setCellValue('H' . $start, strtoupper($d->in_stock));
                $sheet->setCellValue('I' . $start, number_format($d->hpp));
                $sheet->setCellValue('J' . $start, number_format(($d->hpp * $d->in_stock)));
                $start++;
            }
        } else {
            $sheet->setCellValue('A' . $start, "data not found");
            $sheet->mergeCells('A' . $start . ':J' . $start + 1);
        }

        $sheet->getStyle('A5:J' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:J' . $start)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A5:J' . $start - 1)->applyFromArray($styleArray);
        $sheet->getStyle('A5:J' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


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

    public function jsonExportExcelOutbound(Request $req)
    {
        $sql = "SELECT coalesce(date_format(b.date_trans,'%m/%d/%Y'),'-')dates , a.name_material , a.no_material , a.uniqid, b.no_surat_jalan ,
        a.units , a.packaging  , a.qtyUnits  , a.qtyPackaging 
        FROM vw_tbl_inbound_detail a
        inner join vw_tbl_outbound  b on b.id  = a.headers_id 
        WHERE b.status in ('close') and  date_format(b.date_trans,'%Y-%m-%d') between '$req->startDate' AND '$req->endDate' ";

        if ($req->customer_id != "*") {
            $sql .= " AND b.customer_id='" . $req->customer_id . "'";
        }

        if ($req->material_id != "*") {
            $sql .= " AND a.material_id='" . $req->material_id . "'";
        }
        $data = DB::select($sql);

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add an image to the spreadsheet
        $drawing = new Drawing();
        $drawing->setName('Sample Image');
        $drawing->setDescription('Sample Image');
        $drawing->setPath('assets/images/logo-rim.jpg'); // Path to your image file
        $drawing->setHeight(100); // Set the height of the image
        $drawing->setCoordinates('A1'); // Set the cell where the image should be placed
        $sheet->mergeCells('A1:E5');
        $drawing->setWorksheet($sheet);

        // Set some data in the spreadsheet
        $sheet->setCellValue('A8', 'No');
        $sheet->mergeCells('A8:A9');
        $sheet->setCellValue('B8', 'Surat Jalan');
        $sheet->mergeCells('B8:B9');
        $sheet->setCellValue('C8', 'Date');
        $sheet->mergeCells('C8:C9');
        $sheet->setCellValue('D8', 'Material');
        $sheet->setCellValue('D9', 'Name');
        $sheet->setCellValue('E9', 'Part Number');
        $sheet->setCellValue('F9', 'Unique Id');
        $sheet->mergeCells('D8:F8');
        $sheet->setCellValue('G8', 'Detail');
        $sheet->setCellValue('G9', 'Unit');
        $sheet->setCellValue('H9', 'Packaging');
        $sheet->mergeCells('G8:H8');
        $sheet->setCellValue('I8', 'Detail Qty');
        $sheet->setCellValue('I9', 'Qty Unit');
        $sheet->setCellValue('J9', 'Qty Packaging');
        $sheet->mergeCells('I8:J8');

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
        $sheet->getStyle('A8:J9')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8fc03'], // Magenta background
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Example: Freeze the first row
        $sheet->freezePane('E3');
        // Auto size columns based on the content
        $this->autoSizeColumns($sheet, range('A', 'J'));

        $start = 10;
        $no = 1;

        if (count($data) > 0) {
            foreach ($data as $d) {
                $sheet->setCellValue('A' . $start, $no++);
                $sheet->setCellValue('B' . $start, $d->no_surat_jalan);
                $sheet->setCellValue('C' . $start, $d->dates);
                $sheet->setCellValue('D' . $start, strtoupper($d->name_material));
                $sheet->setCellValue('E' . $start, $d->no_material);
                $sheet->setCellValue('F' . $start, strtoupper($d->uniqid));
                $sheet->setCellValue('G' . $start, strtoupper($d->units));
                $sheet->setCellValue('H' . $start, strtoupper($d->packaging));
                $sheet->setCellValue('I' . $start, strtoupper($d->qtyUnits));
                $sheet->setCellValue('J' . $start, strtoupper($d->qtyPackaging));
                $start++;
            }
        } else {
            $sheet->setCellValue('A' . $start, "data not found");
            $sheet->mergeCells('A' . $start . ':J' . $start + 1);
        }

        $sheet->getStyle('A8:J' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:J' . $start)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:J' . $start - 1)->applyFromArray($styleArray);
        $sheet->getStyle('A8:J' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


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

    public function jsonExportPdf(Request $req) {}
}
