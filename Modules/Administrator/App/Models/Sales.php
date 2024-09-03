<?php

namespace Modules\Administrator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Sales extends Model
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
        $qry = "SELECT COUNT(1) AS count FROM tbl_trn_header_trans a  left join tbl_mst_level_member b on b.id = a.member_id ";
        if ($req->search) {
            $qry .= " WHERE no_transaksi LIKE '%$req->search%' ";
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
        $query = "SELECT a.* , b.name_level FROM tbl_trn_header_trans a  left join tbl_mst_level_member b on b.id = a.member_id ";
        if ($req->search) {
            $query .= " WHERE no_transaksi LIKE '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                => $item->id,
                'date_trans'        => $item->date_trans,
                'name_level'        => $item->name_level,
                'status_bayar'      => $item->status_bayar,
                'no_transaksi'      => $item->no_transaksi,
                'status_bayar'      => $item->status_bayar,
                'uang_bayar'        => $item->uang_bayar,
                'total_bayar'       => $item->total_bayar,
                'sub_total'         => $item->sub_total,
                'total_potongan'    => $item->total_potongan,
                'kembalian'         => $item->kembalian,
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

    public static function jsonListDetail($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM tbl_trn_detail_sales  WHERE header_id = '$req->id' ";

        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT * FROM tbl_trn_detail_sales  WHERE header_id = '$req->id' ";

        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'              => $item->id,
                'item_name'       => $item->item_name,
                'unit_name'       => $item->unit_name,
                'out_stock'       => $item->out_stock,
                'discount'        => $item->discount,
                'harga_jual'      => $item->harga_jual,
                'total'           => $item->out_stock * $item->harga_jual  - $item->discount,
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
