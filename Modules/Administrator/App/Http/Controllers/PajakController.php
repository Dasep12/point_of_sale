<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Pajak;
use Modules\Administrator\App\Models\Category;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::pajak/index');
    }

    public function jsonPajak(Request $req)
    {
        $response = Pajak::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = Pajak::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonDetail(Request $req)
    {
        $response = Pajak::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Pajak::find($req->id);
            $cust->name = $req->name;
            $cust->code_pajak = $req->code_pajak;
            $cust->persentase = $req->persentase;
            $cust->status_pajak = $req->status_pajak == null ? 0 : 1;
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
        $resp  = Pajak::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonMultiDelete(Request $req)
    {
        DB::beginTransaction();
        try {
            Pajak::whereIn('id', $req->id)->delete();
            DB::commit();
            return response()->json(['success' => true, 'msg' => 'Berhasil Delete']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function jsonForListPajak(Request $request)
    {
        $query = $request->get('q');
        $results = Pajak::where('name', 'LIKE', "%{$query}%")->get(['id', 'name']);
        return response()->json($results, 200);
    }
}
