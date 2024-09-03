<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Administrator\App\Models\Category;
use Modules\Administrator\App\Models\Customers;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Location;
use Modules\Administrator\App\Models\Material;
use Modules\Administrator\App\Models\Price;
use Modules\Administrator\App\Models\Units;
use Modules\Administrator\App\Models\Warehouse;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'warehouse' => Warehouse::where('status_warehouse', 1)->get(),
            'categ'     => Category::where('status_categories', 1)->get(),
            'units'      => Units::where('status_unit', 1)->get(),
            'member'      => LevelMember::where('status_level', 1)->get(),
        ];
        return view('administrator::material/index', $data);
    }

    public function jsonMaterial(Request $req)
    {
        $response = Material::jsonList($req);
        return response()->json($response);
    }


    public function jsonCreate(Request $req)
    {
        $resp = Material::jsonCreate($req);
        return response()->json($resp);
    }

    public function jsonDetail(Request $req)
    {
        $response = Material::find($req->id);
        return response()->json($response);
    }

    public function jsonDetailPrice(Request $req)
    {
        $response = Material::jsonListPrice($req);
        return response()->json($response);
    }

    public function jsonUpdate(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Material::find($req->id);
            $cust->kode_item         =  $req->kode_item;
            $cust->barcode           =  $req->barcode;
            $cust->name_item         =  $req->name_item;
            $cust->categori_id       =  $req->categori_id;
            $cust->unit_id           =  $req->unit_id;
            $cust->merek             =  $req->merek;
            $cust->satuan_dasar      =  $req->satuan_dasar;
            $cust->konversi_satuan   =  $req->konversi_satuan;
            $cust->harga_pokok       =  $req->harga_pokok;
            $cust->stock_minimum     =  $req->stock_minimum;
            $cust->tipe_item         =  $req->tipe_item;
            $cust->serial            =  $req->serial;
            $cust->location_id       =  $req->location_id;
            $cust->warehouse_id      =  $req->warehouse_id;
            $cust->remarks           =  $req->remarks;
            $cust->updated_at        = date('Y-m-d H:i:s');
            $cust->updated_by        = session()->get("user_id");
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
        $resp  = Material::jsonDelete($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonLocation(Request $req)
    {
        $data = Location::where(["warehouse_id" => $req->warehouse_id, 'status_location' => 1])->get();
        return response()->json($data);
    }


    // PRICE
    public function jsonCreatePrice(Request $req)
    {
        $resp = Material::jsonCreatePrice($req);
        return response()->json(['msg' => $resp]);
    }

    public function jsonUpdatePrice(Request $req)
    {
        DB::beginTransaction();
        try {
            $cust = Price::find($req->idPrice);
            $cust->member_id         =  $req->member_id;
            $cust->harga_jual        =  $req->harga_jual;
            $cust->updated_at        = date('Y-m-d H:i:s');
            $cust->updated_by        = session()->get("user_id");
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

    public function jsonDeletePrice(Request $req)
    {
        $resp  = Material::jsonDeletePrice($req);
        return response()->json(['msg' => $resp]);
    }
}
