<?php

namespace Modules\Administrator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Beli extends Model
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
        $qry = "SELECT COUNT(1) AS count from tbl_trn_header_trans 
        left join (
           select count(1) total , header_id from tbl_trn_detail_beli
           group by header_id
        ) X on X.header_id = id
        where type in ('in') and types in ('beli') 
        ";
        if ($req->search) {
            $qry .= " AND no_transaksi LIKE '%$req->search%' ";
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
        $query = "SELECT * , X.total_item from tbl_trn_header_trans left join (
           select count(1) total_item , header_id from tbl_trn_detail_beli
           group by header_id
        ) X on X.header_id = id
        where type in ('in') and types in ('beli') ";
        if ($req->search) {
            $query .= " AND no_transaksi LIKE '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                => $item->id,
                'date_trans'        => $item->date_trans,
                'no_transaksi'      => $item->no_transaksi,
                'total_item'        => $item->total_item,
                'status_bayar'      => $item->status_bayar,
                'total_bayar'       => $item->total_bayar,
                'created_at'        => $item->created_at,
                'created_by'        => $item->created_by,
                'updated_at'        => $item->updated_at,
                'updated_by'        => $item->updated_by,
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

    public static function jsonListDetailBeli($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM tbl_trn_detail_beli  WHERE header_id = '$req->id' ";

        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT * FROM tbl_trn_detail_beli  WHERE header_id = '$req->id' ";

        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'              => $item->id,
                'supplier'        => $item->supplier_name,
                'item_name'       => $item->item_name,
                'unit_name'       => $item->unit_name,
                'in_stock'        => $item->in_stock,
                'hpp'             => $item->hpp,
                'total'           => $item->in_stock * $item->hpp,
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
