<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'level' => LevelMember::where('status_level', 1)->get()
        ];
        return view('administrator::member/index', $data);
    }

    public function jsonMember(Request $req)
    {
        $response = Member::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = Member::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }



    public function jsonDetail(Request $req)
    {
        $response = Member::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Member::find($req->id);
            $cust->levelMember_id = $req->levelMember_id;
            $cust->kode_member = $req->kode_member;
            $cust->status_member = $req->status_member == null ? 0 : 1;
            $cust->remarks = $req->remarks;
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
        $resp  = Member::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonForListUnit(Request $request)
    {
        $parent = $request->get('parent');
        $results = Member::where(['parent_id' => $parent, 'status_unit' => 1])->get(['id', 'name_unit']);
        return response()->json($results, 200);
    }
}
