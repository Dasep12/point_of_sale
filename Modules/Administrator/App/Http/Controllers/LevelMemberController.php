<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;

class LevelMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'level' => ''
        ];
        return view('administrator::level_member/index', $data);
    }

    public function jsonLevelMember(Request $req)
    {
        $response = LevelMember::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = LevelMember::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }



    public function jsonDetail(Request $req)
    {
        $response = LevelMember::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = LevelMember::find($req->id);
            $cust->name_level = $req->name_level;
            $cust->status_level = $req->status_level == null ? 0 : 1;
            $cust->updated_at = date('Y-m-d H:i:s');
            $cust->updated_by = session()->get("user_id");
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
        $resp  = LevelMember::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonForListUnit(Request $request)
    {
        $parent = $request->get('parent');
        $results = LevelMember::where(['parent_id' => $parent, 'status_unit' => 1])->get(['id', 'name_unit']);
        return response()->json($results, 200);
    }
}
