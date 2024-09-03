<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Users;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::users/index');
    }



    public function jsonUsers(Request $req)
    {
        $response = Users::jsonList($req);
        return response()->json($response);
    }

    public function jsonListMenuForUsers(Request $req)
    {

        $response = Users::jsonDetailListMenu($req);
        return response()->json($response);
    }


    public function jsonCreate(Request $req)
    {


        $resp = Users::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonDetail(Request $req)
    {
        $response = Users::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Users::find($req->id);
            $cust->username = $req->username;
            $cust->fullname = $req->fullname;
            $cust->phone = $req->phone;
            $cust->email = $req->email;
            $cust->role_id = $req->role_id;
            $cust->customers_id = $req->customers_id;
            $cust->password = $req->password;
            $cust->status_user = $req->status_user == null ? 0 : 1;
            $cust->updated_at = date('Y-m-d H:i:s');
            $cust->updated_by = session()->get("user_id");

            // add new roles 
            $menuItems = [];
            $checkedAddMenu = $req->addMenu;
            $checkedEditMenu = $req->editMenu;
            $checkedDeleteMenu = $req->deleteMenu;
            $allMenus = $req->idAccessMenu;
            foreach ($allMenus as $key => $val) {
                $data = array(
                    'user_id'       => $req->id,
                    'accessmenu_id' => $allMenus[$key],
                    'add' =>  $checkedAddMenu != null ? in_array($allMenus[$key], $checkedAddMenu, true) : 0,
                    'edit' => $checkedEditMenu != null ? in_array($allMenus[$key], $checkedEditMenu, true) : 0,
                    'delete' => $checkedEditMenu != null ? in_array($allMenus[$key], $checkedDeleteMenu, true) : 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => 1
                );
                array_push($menuItems, $data);
            }

            DB::table('tbl_sys_accesmenu')->where('user_id', $req->id)->delete();
            DB::table("tbl_sys_accesmenu")
                ->insert($menuItems);

            try {
                $cust->save();
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
        $resp  = Users::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }
}
