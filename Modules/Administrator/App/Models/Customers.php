<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Customers extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_customers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name_customers',
        'code_customers',
        'address',
        'city',
        'phone',
        'email',
        'status_customer',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    private $tables =  'tbl_mst_customers';


    // protected static function newFactory(): MaterialFactory
    // {
    //     //return MaterialFactory::new();
    // }

    public static function jsonList($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM tbl_mst_customers ";
        if ($req->search) {
            $qry .= " WHERE name_customers LIKE '%$req->search%' ";
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
        $query = "SELECT * FROM tbl_mst_customers";
        if ($req->search) {
            $query .= " WHERE name_customers LIKE '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'            => $item->id,
                'name_customers' => $item->name_customers,
                'code_customers' => $item->code_customers,
                'address'       => $item->address,
                'city'          => $item->city,
                'phone'         => $item->phone,
                'email'         => $item->email,
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
                DB::table('tbl_mst_customers')
                    ->insert([
                        'name_customers' => $req->name_customers,
                        'code_customers' => $req->code_customers,
                        'address' => $req->address,
                        'city' => $req->city,
                        'phone' => $req->phone,
                        'email' => $req->email,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => session()->get("user_id"),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => session()->get("user_id"),
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
            DB::table('tbl_mst_customers')->where('id', $req->id)->delete();
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
