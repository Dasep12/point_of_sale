<?php

namespace Modules\Administrator\App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_mst_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'username',
        'fullname',
        'password',
        'role_id',
        'email',
        'phone',
        'status_user',
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
        $qry = "SELECT COUNT(a.id) AS count from tbl_mst_users a
        left join tbl_mst_role b on b.id  = a.role_id WHERE b.roleName != 'Developer' ";
        if ($req->search) {
            $qry .= " AND username like '%$req->search%' ";
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
        $query = "SELECT a.* , b.roleName from tbl_mst_users a
                left join tbl_mst_role b on b.id  = a.role_id  WHERE b.roleName != 'Developer'";
        if ($req->search) {
            $query .= "  AND username like '%$req->search%' ";
        }
        $query .= " ORDER BY  id  DESC  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id'                 => $item->id,
                'roleName'           => $item->roleName,
                'fullname'           => $item->fullname,
                'username'           => $item->username,
                'role_id'            => $item->role_id,
                'password'           => $item->password,
                'phone'              => $item->phone,
                'email'              => $item->email,
                'status_user'        => $item->status_user,
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
        $query = "SELECT X.id id_accessMenu , a.Menu_id, a.MenuName , a.MenuIcon , a.MenuLevel , a.LevelNumber , IFNULL(X.enable_menu,0)enable_menu ,
           IFNULL(Y.add,0) addMenu , IFNULL(Y.edit,0) editMenu , IFNULL(Y.delete,0) deleteMenu
            from tbl_sys_menu a
            left outer join (
            select tsr.id ,  tsr.menu_id , tsr.enable_menu from tbl_sys_roleaccessmenu tsr
            where tsr.role_id = '" . $req->role_id . "'
            )X on a.Menu_id  = X.menu_id
            left outer join (
            select tsa.accessmenu_id,tsa.add , tsa.edit , tsa.delete from tbl_sys_accesmenu tsa
            where user_id = '" . $req->user_id . "'
            )Y on Y.accessmenu_id = X.id 
            ORDER BY 
            SUBSTRING(a.MenuUrut, 4) + 0,
            CASE
                WHEN a.ParentMenu = '*' THEN a.Menu_id
                ELSE a.ParentMenu 
            END,
            CASE
                WHEN a.ParentMenu = '*' THEN 0 
                ELSE SUBSTRING(a.MenuUrut, 4) + 0 
            END";
        $query .= "  LIMIT  $start , $limit ";
        $data = DB::select($query);

        // Prepare rows for jqGrid
        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                'id_accessMenu'      => $item->id_accessMenu,
                'Menu_id'            => $item->Menu_id,
                'MenuName'           => $item->MenuName,
                'statsMenu'          => $item->enable_menu,
                'addMenu'            => $item->addMenu,
                'editMenu'           => $item->editMenu,
                'deleteMenu'         => $item->deleteMenu,
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

            DB::table('tbl_mst_users')
                ->insert([
                    'username'          => $req->username,
                    'fullname'          => $req->fullname,
                    'password'          => $req->password,
                    'lock_user'         => $req->lock_user,
                    'email'             => $req->email,
                    'phone'             => $req->phone,
                    'role_id'           => $req->role_id,
                    'customers_id'      => $req->customers_id,
                    'phone'             => $req->phone,
                    'status_user'       => $req->status_user == null ? 0 : 1,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => session()->get("user_id"),
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => session()->get("user_id"),
                ]);
            $lastId  = DB::getPdo()->lastInsertId();
            $menuItems = [];
            $checkedAddMenu = $req->addMenu;
            $checkedEditMenu = $req->editMenu;
            $checkedDeleteMenu = $req->deleteMenu;
            $allMenus = $req->idAccessMenu;
            foreach ($allMenus as $key => $val) {
                $data = array(
                    'user_id'       => $lastId,
                    'accessmenu_id' => $allMenus[$key],
                    'add' =>  $checkedAddMenu != null ? in_array($allMenus[$key], $checkedAddMenu, true) : 0,
                    'edit' => $checkedEditMenu != null ? in_array($allMenus[$key], $checkedEditMenu, true) : 0,
                    'delete' => $checkedEditMenu != null ? in_array($allMenus[$key], $checkedDeleteMenu, true) : 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => session()->get("user_id")
                );
                array_push($menuItems, $data);
            }

            DB::table("tbl_sys_accesmenu")
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
            DB::table('tbl_sys_accesmenu')->where('user_id', $req->id)->delete();
            DB::table('tbl_mst_users')->where('id', $req->id)->delete();
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
