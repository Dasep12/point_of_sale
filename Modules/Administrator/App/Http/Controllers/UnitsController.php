<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Units;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'unitParent' => Units::get()
        ];
        return view('administrator::units/index', $data);
    }

    public function jsonUnits(Request $req)
    {
        $response = Units::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = Units::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }



    public function jsonDetail(Request $req)
    {
        $response = Units::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Units::find($req->id);
            $cust->unit_name = $req->unit_name;
            $cust->unit_code = $req->unit_code;
            $cust->status_unit = $req->status_unit == null ? 0 : 1;
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
        $resp  = Units::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonForListUnit(Request $request)
    {
        $parent = $request->get('parent');
        $results = Units::where(['parent_id' => $parent, 'status_unit' => 1])->get(['id', 'name_unit']);
        return response()->json($results, 200);
    }
}
