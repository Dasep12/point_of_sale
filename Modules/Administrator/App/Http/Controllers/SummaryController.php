<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Material;
use Modules\Administrator\App\Models\SummaryStock;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::summary/index');
    }

    public function jsonSummary(Request $req)
    {
        $response = SummaryStock::jsonSummary($req);
        return response()->json($response);
    }
    public function jsonDetailSummary(Request $req)
    {
        $response = SummaryStock::jsonDetailSummary($req);
        return response()->json($response);
    }

    public function jsonListMaterialSummary(Request $req)
    {
        $res = Material::where('customers_id', $req->customer_id)->get();
        return response()->json($res);
    }


    public function jsonExportExcel(Request $req)
    {
        $sql = "SELECT d.name_material, d.uniqueId, d.no_material 
                , a.types_trans , a.types	, a.unit,a.units,a.packaging,
                a.QtyUnit , a.QtyUnits,a.QtyPackaging , 
                a.begin_stock_unit , a.begin_stock_units,a.begin_stock_packaging ,
                a.EndStockUnit , a.EndStockUnits , a.EndStockPackaging ,
                date_format(a.dates,'%m/%d/%Y')dates
                FROM vw_tbl_control_stock_detail a
                left join tbl_trn_shipingmaterial b on b.id = a.headers_id 
                left join tbl_mst_customers c on c.id  = b.customer_id 
                left join tbl_mst_material d on d.id  = a.material_id WHERE date_format(a.dates,'%Y-%m-%d') between '$req->startDate' AND '$req->endDate' ";

        if ($req->customer_id != "*") {
            $sql .= " AND a.customer_id='" . $req->customer_id . "'";
        }

        if ($req->material_id != "*") {
            $sql .= " AND a.material_id='" . $req->material_id . "'";
        }

        $sql .= " ORDER BY d.no_material ASC";
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
        $sheet->setCellValue('B8', 'Date');
        $sheet->mergeCells('B8:B9');
        $sheet->setCellValue('C8', 'Material');
        $sheet->setCellValue('C9', 'Name');
        $sheet->setCellValue('D9', 'Part Number');
        $sheet->setCellValue('E9', 'Unique Id');
        $sheet->mergeCells('C8:E8');
        $sheet->setCellValue('F8', 'Types');
        $sheet->setCellValue('F9', 'Trans');
        $sheet->setCellValue('G9', 'Type');
        $sheet->mergeCells('F8:G8');
        $sheet->setCellValue('H8', 'Detail');
        $sheet->setCellValue('H9', 'Unit');
        $sheet->setCellValue('I9', 'Packaging');
        $sheet->mergeCells('H8:I8');
        $sheet->setCellValue('J8', 'Begin Stock');
        $sheet->setCellValue('J9', 'Qty Unit');
        $sheet->setCellValue('K9', 'Qty Packaging');
        $sheet->mergeCells('J8:K8');
        $sheet->setCellValue('L8', 'IN/OUT Stock');
        $sheet->setCellValue('L9', 'Qty Unit');
        $sheet->setCellValue('M9', 'Qty Packaging');
        $sheet->mergeCells('L8:M8');
        $sheet->setCellValue('N8', 'Final Stock');
        $sheet->setCellValue('N9', 'Qty Unit');
        $sheet->setCellValue('O9', 'Qty Packaging');
        $sheet->mergeCells('N8:O8');

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
        $sheet->getStyle('A8:O9')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'f8fc03'], // Magenta background
            ],
            'font' => [
                'bold' => true,
            ],
        ]);

        // Example: Freeze the first row
        $sheet->freezePane('E8');
        // Auto size columns based on the content
        $this->autoSizeColumns($sheet, range('A', 'O'));

        $start = 10;
        $no = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $start, $no++);
            $sheet->setCellValue('B' . $start, $d->dates);
            $sheet->setCellValue('C' . $start, strtoupper($d->name_material));
            $sheet->setCellValue('D' . $start, $d->no_material);
            $sheet->setCellValue('E' . $start, strtoupper($d->uniqueId));
            $sheet->setCellValue('F' . $start, strtoupper($d->types_trans));
            $sheet->setCellValue('G' . $start, strtoupper($d->types));
            $sheet->setCellValue('H' . $start, strtoupper($d->units));
            $sheet->setCellValue('I' . $start, strtoupper($d->packaging));
            $sheet->setCellValue('J' . $start, $d->begin_stock_units);
            $sheet->setCellValue('K' . $start, $d->begin_stock_packaging);
            $sheet->setCellValue('L' . $start, $d->QtyUnits);
            $sheet->setCellValue('M' . $start, $d->QtyPackaging);
            $sheet->setCellValue('N' . $start, $d->EndStockUnits);
            $sheet->setCellValue('O' . $start, $d->EndStockPackaging);
            $start++;
        }

        $sheet->getStyle('A8:O' . $start)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A8:O' . $start)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A8:O' . $start - 1)->applyFromArray($styleArray);


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


        // return response()->json($data);

    }


    public function jsonExportExcel2(Request $req) {}

    private function autoSizeColumns($sheet, array $columns)
    {
        foreach ($columns as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
