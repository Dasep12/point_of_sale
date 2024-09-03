<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Warehouse;
use Modules\Administrator\App\Models\Roles;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::roles/index');
    }

    public function jsonRole(Request $req)
    {
        $response = Roles::jsonList($req);
        return response()->json($response);
    }
    public function jsonDetailListMenu(Request $req)
    {
        $response = Roles::jsonDetailListMenu($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {

        $resp = Roles::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonDetail(Request $req)
    {
        $response = Warehouse::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Roles::find($req->id);
            $cust->roleName = $req->roleName;
            $cust->code_role = $req->code_role;
            $cust->status_role = $req->status_role == null ? 0 : 1;
            $cust->updated_at = date('Y-m-d H:i:s');
            $cust->updated_by = session()->get("user_id");

            $allMenus = $req->allMenu;
            $checked = $req->checkedMenu;

            foreach ($allMenus as $key => $val) {
                $data = array(
                    'menu_id' => $allMenus[$key],
                    'enable_menu' => in_array($allMenus[$key], $checked, true),
                    'role_id' => $req->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_by' => session()->get("user_id"),
                    'created_by' => session()->get("user_id")
                );

                $find = DB::table("tbl_sys_roleaccessmenu")
                    ->where(['role_id' =>  $req->id, "menu_id" => $allMenus[$key]])
                    ->get();
                if (count($find) <= 0) {
                    DB::table("tbl_sys_roleaccessmenu")
                        ->insert($data);
                } else {
                    DB::table("tbl_sys_roleaccessmenu")
                        ->where(["role_id" => $req->id, "menu_id" => $allMenus[$key]])
                        ->update($data);
                }
            }

            $cust->save();
            try {
                DB::commit();
                return response()->json(['msg' => 'success']);
            } catch (Exception $ex) {
                return response()->json(['msg' => $ex->getMessage()]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['msg' => $e->getMessage()]);
        }
    }

    public function jsonDelete(Request $req)
    {
        $resp  = Roles::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonForListRoles(Request $request)
    {
        // 
        $query = $request->get('q');
        $results = Roles::where('roleName', '!=', 'Developer')->get(['id', 'roleName']);
        return response()->json($results, 200);
    }
}
