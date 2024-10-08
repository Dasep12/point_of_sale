<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Location;
use Modules\Administrator\App\Models\Warehouse;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::warehouse/index');
    }

    public function jsonWarehouse(Request $req)
    {
        $response = Warehouse::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = Warehouse::jsonCreate($req);
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
            $cust = Warehouse::find($req->id);
            $cust->NameWarehouse = $req->NameWarehouse;
            $cust->Area = $req->Area;
            $cust->phone = $req->phone;
            $cust->fax = $req->fax;
            $cust->code_gudang = $req->code_gudang;
            $cust->Address = $req->Address;
            $cust->status_warehouse = $req->status_warehouse == null ? 0 : 1;
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
        $location = Location::where('warehouse_id', $req->id);
        if ($location->count() > 0) {
            return response()->json(['msg' => 'Warehouse Masih Memiliki Lokasi Aktif,Tidak Bisa di Hapus']);
        } else {
            $resp  = Warehouse::jsonDelete($req);
            return response()->json(['msg' => $resp]);
        }
    }

    public function jsonMultiDelete(Request $req)
    {
        DB::beginTransaction();
        try {
            $location = Location::whereIn('warehouse_id', $req->id);
            if ($location->count() > 0) {
                return response()->json(['msg' => 'Warehouse Masih Memiliki Lokasi Aktif,Tidak Bisa di Hapus']);
            } else {
                Warehouse::whereIn('id', $req->id)->delete();
                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Berhasil Delete']);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function jsonForListWarehouse(Request $request)
    {
        $query = $request->get('q');
        $results = Warehouse::where('nameWarehouse', 'LIKE', "%{$query}%")->get(['id', 'nameWarehouse']);
        return response()->json($results, 200);
    }
}
