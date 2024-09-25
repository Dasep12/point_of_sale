<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Category;
use Modules\Administrator\App\Models\Material;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::category/index');
    }

    public function jsonCategory(Request $req)
    {
        $response = Category::jsonList($req);
        return response()->json($response);
    }

    public function jsonCreate(Request $req)
    {
        $resp = Category::jsonCreate($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonDetail(Request $req)
    {
        $response = Category::find($req->id);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Category::find($req->id);
            $cust->name_categories = $req->name_categories;
            $cust->code_categories = $req->code_categories;
            $cust->remarks = $req->remarks;
            $cust->status_categories = $req->status_categories == null ? 0 : 1;
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
        $material = Material::where('categori_id', $req->id);
        if ($material->count() > 0) {
            return response()->json(['msg' => 'Category Masih Terikat di Product Aktif,Tidak Bisa di Hapus']);
        } else {
            $resp  = Category::jsonDelete($req);
            return response()->json(['msg' => $resp]);
        }
    }

    public function jsonForListCategory(Request $request)
    {
        $query = $request->get('q');
        $results = Category::where('name_categories', 'LIKE', "%{$query}%")->get(['id', 'name_categories']);
        return response()->json($results, 200);
    }
}
