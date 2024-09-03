<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class SummaryStock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_trn_summaryStock';
    protected $primaryKey = 'id';
    protected $fillable = [
        'material_id',
        'begin_stock',
        'inbound_stock',
        'end_stock',
        'created_at',
        'updated_at',
        'created_by',
        'headers_detail_id'
    ];

    public static function jsonSummary($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;
        $custId = session()->get("customers_id")  == "*" ? null : session()->get("customers_id");
        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM vw_tbl_control_stock  ";
        if ($custId != null) {
            $qry .= " WHERE customers_id = '$custId' ";
        } else {
            $qry .= " WHERE customers_id !='$custId' ";
        }
        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT * FROM vw_tbl_control_stock";
        if ($custId != null) {
            $query .= " WHERE customers_id = '$custId' ";
        } else {
            $query .= " WHERE customers_id !='$custId' ";
        }

        $query .= " ORDER BY updated_at DESC LIMIT  $start , $limit  ";



        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                => $item->id,
                'uniqueId'          => $item->uniqueId,
                'units'             => $item->units,
                'unit'              => $item->unit,
                'packaging'         => $item->packaging,
                'name_material'     => $item->name_material,
                'no_material'        => $item->no_material,
                'QtyUnit'           => $item->QtyUnit,
                'QtyUnits'          => $item->QtyUnits,
                'QtyPackaging'      => $item->QtyPackaging,
                'updated_at'      => $item->updated_at,
                'cell' => [
                    $item->id,
                ] // Adjust fields as needed
            ];
        }

        $response = [
            'page' => $page,
            'total' => $total_pages,
            'records' => $count,
            'rows' => $rows
        ];
        return $response;
    }

    public static function jsonDetailSummary($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM vw_tbl_control_stock_detail WHERE material_id = '" . $req->id . "'   ";

        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT * FROM vw_tbl_control_stock_detail WHERE material_id = '" . $req->id . "' ORDER BY dates DESC LIMIT  $start , $limit  ";

        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'unit'                  => $item->unit,
                'units'                 => $item->units,
                'dates'                 => $item->dates,
                'packaging'             => $item->packaging,
                'types'                 => strtoupper($item->types),
                'types_trans'           => strtoupper($item->types_trans),
                'QtyUnit'               => $item->QtyUnit,
                'QtyUnits'              => $item->QtyUnits,
                'QtyPackaging'          => $item->QtyPackaging,
                'dates'                 => $item->dates,
                'begin_stock_unit'      => $item->begin_stock_unit,
                'begin_stock_units'     => $item->begin_stock_units,
                'begin_stock_packaging' => $item->begin_stock_packaging,
                'EndStockUnit'          => $item->EndStockUnit,
                'EndStockUnits'         => $item->EndStockUnits,
                'EndStockPackaging'     => $item->EndStockPackaging,
                'cell' => [
                    $item->id,
                ] // Adjust fields as needed
            ];
        }

        $response = [
            'page' => $page,
            'total' => $total_pages,
            'records' => $count,
            'rows' => $rows
        ];
        return $response;
    }
}
