<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_member';
    protected $primaryKey = 'id';
    protected $fillable = [
        'levelMember',
        'kode_member',
        'status_member',
        'remarks',
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
        $qry = "SELECT COUNT(1) AS count FROM tbl_mst_member a 
        left join tbl_mst_level_member b on a.levelMember_id = b.id ";
        if ($req->search) {
            $qry .= " WHERE kode_member LIKE '%$req->search%' ";
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
        $query = "SELECT a.* , b.name_level FROM tbl_mst_member  a 
        left join tbl_mst_level_member b on a.levelMember_id = b.id";
        if ($req->search) {
            $query .= " WHERE kode_member LIKE '%$req->search%' ";
        }
        $query .= " ORDER BY  a.id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'            => $item->id,
                'name_level'     => $item->name_level,
                'levelMember'     => $item->levelMember_id,
                'kode_member'     => $item->kode_member,
                'status_member'   => $item->status_member,
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
                DB::table('tbl_mst_member')
                    ->insert([
                        'levelMember_id'     => $req->levelMember_id,
                        'kode_member'     => $req->kode_member,
                        'status_member'   => $req->status_member == null ? 0 : 1,
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
