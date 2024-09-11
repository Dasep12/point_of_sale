<?php

namespace Modules\Administrator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\Database\factories\MaterialFactory;

class Material extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_material';
    protected $primaryKey = 'id';
    protected $fillable = [
        'kode_item',
        'barcode',
        'name_item',
        'categori_id',
        'unit_id',
        'merek',
        'satuan_dasar',
        'konversi_satuan',
        'harga_pokok',
        'harga_jual_member_1',
        'harga_jual_member_2',
        'harga_umum',
        'stock',
        'stock_minimum',
        'tipe_item',
        'serial',
        'location_id',
        'warehouse_id',
        'remarks',
        'status_item',
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
        $qry = "SELECT COUNT(1) AS count 
                FROM tbl_mst_material a 
                left join tbl_mst_units b on b.id = a.unit_id 
                left join tbl_mst_rak c on c.id = a.location_id
                left join tbl_mst_categories d on d.id = a.categori_id
              ";

        if ($req->search) {
            $qry .= " WHERE a.name_item LIKE '%$req->search%'  ";
        }
        // Total count of records
        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT a.* , b.unit_code , f.location , d.code_categories
                FROM tbl_mst_material a 
                left join tbl_mst_units b on b.id = a.unit_id 
                left join tbl_mst_rak f on f.id = a.location_id
                left join tbl_mst_categories d on d.id = a.categori_id ";

        if ($req->search) {
            $qry .= " WHERE a.name_item like '%$req->search% ";
        }



        $query .= " ORDER BY  a.id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                    => $item->id,
                'kode_item'             => $item->kode_item,
                'barcode'               => $item->barcode,
                'name_item'             => $item->name_item,
                'categori_id'           => $item->categori_id,
                'unit_id'               => $item->unit_id,
                'merek'                 => $item->merek,
                'satuan_dasar'          => $item->satuan_dasar,
                'konversi_satuan'       => $item->konversi_satuan,
                'harga_pokok'           => $item->harga_pokok,
                'harga_jual_member_1'   => $item->harga_jual_member_1,
                'harga_jual_member_2'   => $item->harga_jual_member_2,
                'harga_umum'            => $item->harga_umum,
                'stock'                 => $item->stock,
                'stock_minimum'         => $item->stock_minimum,
                'tipe_item'             => $item->tipe_item,
                'serial'                => $item->serial,
                'location_id'           => $item->location_id,
                'warehouse_id'          => $item->warehouse_id,
                'remarks'               => $item->remarks,
                'unit_code'             => $item->unit_code,
                'location'              => $item->location,
                'code_categories'       => $item->code_categories,
                'status_item'           => $item->status_item,
                'created_at'            => $item->created_at,
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
                DB::table('tbl_mst_material')
                    ->insert([
                        'kode_item'         =>  $req->kode_item,
                        'barcode'           =>  $req->barcode,
                        'name_item'         =>  $req->name_item,
                        'categori_id'       =>  $req->categori_id,
                        'unit_id'           =>  $req->unit_id,
                        'merek'             =>  $req->merek,
                        'satuan_dasar'      =>  $req->satuan_dasar,
                        'konversi_satuan'   =>  $req->konversi_satuan,
                        'harga_pokok'       =>  $req->harga_pokok,
                        'stock_minimum'     =>  $req->stock_minimum,
                        'tipe_item'         =>  $req->tipe_item,
                        'serial'            =>  $req->serial,
                        'location_id'       =>  $req->location_id,
                        'warehouse_id'      =>  $req->warehouse_id,
                        'remarks'           =>  $req->remarks,
                        'status_item'       =>  $req->status_item == null ? 0 : 1,
                        'created_at'        => date('Y-m-d H:i:s'),
                        'created_by'        => session()->get("user_id"),
                        'updated_at'        => date('Y-m-d H:i:s'),
                        'updated_by'        => session()->get("user_id"),
                    ]);
                $lastId = DB::getPdo()->lastInsertId();
                DB::commit();
                $returns = ['msg' => "success", "lastId" => $lastId];
                return $returns;
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
            DB::table('tbl_mst_harga')->where('material_id', $req->id)->delete();
            DB::table('tbl_mst_material')->where('id', $req->id)->delete();
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }


    // PRICE
    public static function jsonListPrice($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;
        $qry = "SELECT COUNT(1) AS count 
                FROM tbl_mst_harga a 
                left join tbl_mst_level_member b on b.id = a.member_id
                WHERE a.material_id = '$req->material_id' 
              ";

        // Total count of records
        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT a.* , b.name_level
                    FROM tbl_mst_harga a 
                    left join tbl_mst_level_member b on b.id = a.member_id
                    WHERE a.material_id = '$req->material_id' 
                ";

        $query .= " ORDER BY  a.id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                    => $item->id,
                'member_id'             => $item->member_id,
                'harga_jual'            => $item->harga_jual,
                'name_level'            => $item->name_level,
                'created_at'            => $item->created_at,
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
    public static function jsonCreatePrice($req)
    {
        DB::beginTransaction();
        try {
            try {
                DB::table('tbl_mst_harga')
                    ->insert([
                        'member_id'         =>  $req->member_id,
                        'material_id'       =>  $req->material_id,
                        'harga_jual'        =>  $req->harga_jual,
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

    public static function jsonDeletePrice($req)
    {
        DB::beginTransaction();
        try {
            DB::table('tbl_mst_harga')->where('id', $req->idPrice)->delete();
            DB::commit();
            return 'success';
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
