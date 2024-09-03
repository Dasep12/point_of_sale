<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class Roles extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_role';
    protected $primaryKey = 'id';
    protected $fillable = [
        'roleName',
        'code_role',
        'status_role',
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
        $qry = "SELECT COUNT(1) AS count FROM tbl_mst_role WHERE  roleName != 'Developer'";
        if ($req->search) {
            $qry .= " AND roleName like '%$req->search%' ";
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
        $query = "SELECT  a.id, 
        a.roleName, a.code_role , a.status_role , a.created_at,a.created_by , a.updated_by,a.updated_at,
        GROUP_CONCAT(CONCAT('[', X.MenuName, ']') order by substr(X.Menu_id,4,3) asc SEPARATOR ' ') AS Accessed
        from tbl_mst_role a
        left outer join (
        select  tsm.MenuName , tsr.role_id ,  tsm.Menu_id  from tbl_sys_roleaccessmenu tsr
        left join tbl_sys_menu tsm on tsm.Menu_id = tsr.menu_id 
        where tsr.enable_menu = 1 
        group by tsm.MenuName , tsr.role_id , tsm.Menu_id
        order by cast(substring(tsm.menu_id,4,3)as int ) asc  
        )X on a.id  = X.role_id
        WHERE roleName != 'Developer' 
        ";
        if ($req->search) {
            $query .= " AND roleName like '%$req->search%' ";
        }
        $query .= "GROUP BY a.id, 
        a.roleName, a.code_role , a.status_role , a.created_at,a.created_by , a.updated_by , a.updated_at  ";

        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                 => $item->id,
                'roleName'           => $item->roleName,
                'code_role'          => $item->code_role,
                'status_role'        => $item->status_role,
                'Accessed'           => $item->Accessed,
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

    public static function jsonDetailListMenu($req)
    {
        $page = $req->input('page');
        $limit = $req->input('rows');
        $sidx = $req->input('sidx', 'id');
        $sord = $req->input('sord', 'asc');
        $start = ($page - 1) * $limit;

        // Total count of records
        $qry = "SELECT COUNT(1) AS count FROM tbl_sys_menu ";

        $countResult = DB::select($qry);
        $count = $countResult[0]->count;

        // Total pages calculation
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        // Fetch data using DB::raw
        $query = "SELECT a.Menu_id , a.ParentMenu , a.MenuName , a.MenuLevel , a.MenuIcon ,a.LevelNumber, X.enable_menu 
        FROM tbl_sys_menu a 
            left join (
                select tsr.enable_menu , tsr.menu_id  from tbl_sys_roleaccessmenu tsr
                where  tsr.role_id = '$req->id'
                group by tsr.menu_id ,tsr.enable_menu
        )X on X.menu_id = a.Menu_id 
        ORDER BY 
        SUBSTRING(a.MenuUrut, 4) + 0,
        CASE
                WHEN a.ParentMenu = '*' THEN a.Menu_id -- Top-level menus first
                ELSE a.ParentMenu -- Then submenus under their respective parents
            END,
            CASE
                WHEN a.ParentMenu = '*' THEN 0 -- Top-level menus first
                ELSE SUBSTRING(a.MenuUrut, 4) + 0 -- Sort submenus numerically by Menu_id
            END";
        $query .= "  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'Menu_id'            => $item->Menu_id,
                'MenuName'           => $item->MenuName,
                'statsMenu'           => $item->enable_menu,
                'MenuIcon'           => $item->MenuIcon,
                'MenuLevel'          => $item->MenuLevel,
                'LevelNumber'        => $item->LevelNumber
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

            DB::table('tbl_mst_role')
                ->insert([
                    'roleName'          => $req->roleName,
                    'code_role'         => $req->code_role,
                    'status_role'       => $req->status_role == null ? 0 : 1,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => session()->get("user_id"),
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => session()->get("user_id"),
                ]);
            $lastId  = DB::getPdo()->lastInsertId();
            $menuItems = [];
            $allMenus = $req->allMenu;
            $checked = $req->checkedMenu;

            foreach ($allMenus as $key => $val) {
                $data = array(
                    'menu_id' => $allMenus[$key],
                    'enable_menu' => in_array($allMenus[$key], $checked, true),
                    'role_id' => $lastId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 1
                );
                array_push($menuItems, $data);
            }
            DB::table("tbl_sys_roleaccessmenu")
                ->insert($menuItems);
            try {
                DB::commit();
                return "success";
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollback();
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
            DB::table('tbl_sys_roleaccessmenu')->where('role_id', $req->id)->delete();
            DB::table('tbl_mst_role')->where('id', $req->id)->delete();
            try {
                DB::commit();
                return "success";
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollback();
                return $ex->getMessage();
            }
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
