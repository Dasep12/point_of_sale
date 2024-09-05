<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_trn_header_trans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_transaksi',
        'type',
        'types',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public static function jsonList($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;
        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM vw_stock_item  ";
        if ($req->search) {
            $qry .= " WHERE name_item like '%$req->search%' ";
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
        $query = "SELECT * FROM vw_stock_item";
        if ($req->search) {
            $query .= " WHERE name_item like '%$req->search%' ";
        }
        $query .= " ORDER BY updated_at DESC LIMIT  $start , $limit  ";

        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                => $item->id,
                'item_name'         => $item->name_item,
                'kode_item'         => $item->kode_item,
                'merek'             => $item->merek,
                'unit_code'         => $item->unit_code,
                'stock_minimum'     => $item->stock_minimum,
                'inStock'           => $item->inStock,
                'outStock'          => $item->outStock,
                'Stock'             => $item->Stock,
                'updated_at'        => $item->updated_at,
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
