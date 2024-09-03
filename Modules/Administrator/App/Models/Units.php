<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Units extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_units';
    protected $primaryKey = 'id';
    protected $fillable = [
        'unit_name',
        'unit_code',
        'status_unit',
        'created_at',
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
        $qry = "SELECT COUNT(1) AS count FROM tbl_mst_units  ";
        if ($req->search) {
            $qry .= " AND unit_name LIKE '%$req->search%' ";
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
        $query = "SELECT * FROM tbl_mst_units ";
        if ($req->search) {
            $query .= " AND unit_name LIKE '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'            => $item->id,
                'unit_name'     => $item->unit_name,
                'unit_code'     => $item->unit_code,
                'status_unit'   => $item->status_unit,
                'remarks'       => $item->remarks,
                'CreatedAt'     => $item->created_at,
                'CreatedBy'     => $item->created_by,
                'UpdatedAt'     => $item->updated_at,
                'UpdatedBy'     => $item->updated_by,
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
                DB::table('tbl_mst_units')
                    ->insert([
                        'name_unit'     => $req->name_unit,
                        'code_unit'     => $req->code_unit,
                        'status_unit'   => $req->status_unit == null ? 0 : 1,
                        'remarks'       => $req->remarks,
                        'created_at'    => date('Y-m-d H:i:s'),
                        'created_by'    => session()->get("user_id"),
                        'updated_at'    => date('Y-m-d H:i:s'),
                        'updated_by'    => session()->get("user_id"),
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
            DB::table('tbl_mst_units')->where('id', $req->id)->delete();
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
