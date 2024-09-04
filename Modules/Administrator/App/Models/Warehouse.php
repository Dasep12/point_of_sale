<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_warehouse';
    protected $primaryKey = 'id';
    protected $fillable = [
        'NameWarehouse',
        'Area',
        'Address',
        'code_gudang',
        'created_at',
        'phone',
        'fax',
        'status_warehouse',
        'created_by',
        'updated_at',
        'updated_by'
    ];



    public static function jsonList($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM tbl_mst_warehouse ";
        if ($req->search) {
            $qry .= " WHERE NameWarehouse like '%$req->search%' ";
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
        $query = "SELECT * FROM tbl_mst_warehouse";
        if ($req->search) {
            $query .= " WHERE NameWarehouse like '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                 => $item->id,
                'NameWarehouse'      => $item->NameWarehouse,
                'Address'            => $item->Address,
                'code_gudang'        => $item->code_gudang,
                'Area'               => $item->Area,
                'phone'              => $item->phone,
                'fax'                => $item->fax,
                'status_warehouse'   => $item->status_warehouse,
                'created_at'         => $item->created_at,
                'created_by'         => $item->created_by,
                'updated_at'         => $item->updated_at,
                'updated_by'         => $item->updated_by,
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

    public static function jsonCreate($req)
    {
        DB::beginTransaction();
        try {
            try {
                DB::table('tbl_mst_warehouse')
                    ->insert([
                        'NameWarehouse'     => $req->NameWarehouse,
                        'Area'              => $req->Area,
                        'phone'             => $req->phone,
                        'code_gudang'       => $req->code_gudang,
                        'fax'               => $req->fax,
                        'Address'           => $req->Address,
                        'status_warehouse'  => $req->status_warehouse == null ? 0 : 1,
                        'created_at'        => date('Y-m-d H:i:s'),
                        'created_by'        => session()->get("user_id"),
                        'updated_at'        => date('Y-m-d H:i:s'),
                        'updated_by'        => session()->get("user_id"),
                    ]);
                DB::commit();
                return "success";
            } catch (\Illuminate\Database\QueryException $ex) {
                return $ex->getMessage();
            }
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public static function jsonDelete($req)
    {
        DB::beginTransaction();
        try {
            DB::table('tbl_mst_warehouse')->where('id', $req->id)->delete();
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
