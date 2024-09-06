<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Customers;
use Modules\Administrator\App\Models\Inbound;
use Modules\Administrator\App\Models\LevelMember;
use Modules\Administrator\App\Models\Material;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $d = DB::table('vw_topsales')
            ->select('item_name', 'qty', 'total_out')
            ->get();
        $data = [
            'salestop' => $d
        ];
        return view('administrator::dashboard/index', $data);
    }

    public function countMember()
    {
        if (session()->get("customers_id") != "*") {
            $data = LevelMember::count();
        } else {
            $data = LevelMember::count();
        }
        return response()->json(['data' => $data]);
    }

    public function countMaterial()
    {
        $data = Material::count();
        return response()->json(['data' => $data]);
    }

    public function countPembelian()
    {
        $data = DB::table('tbl_trn_detail_beli as a')
            ->leftJoin('tbl_trn_header_trans as b', 'b.id', '=', 'a.header_id')
            ->where('types', 'beli')
            ->select(DB::raw('SUM(a.hpp * a.in_stock) as total'))
            ->value('total');
        return response()->json(['data' => $data]);
    }

    public function countPenjualan()
    {
        $data = DB::table('tbl_trn_detail_sales as a')
            ->leftJoin('tbl_trn_header_trans as b', 'b.id', '=', 'a.header_id')
            ->where(['types' => 'sales'])
            ->select(DB::raw('SUM(a.harga_jual * a.out_stock - a.discount) as total'))
            ->value('total');
        return response()->json(['data' => $data]);
    }

    public function countAdjust()
    {
        if (session()->get("customers_id") != "*") {
            $data = Inbound::where(['customers_id' => session()->get("customers_id"), 'types_trans' => 'Adjust'])->count();
        } else {
            $data = Inbound::where(['types_trans' => 'Adjust'])->count();
        }
        return response()->json(['data' => $data]);
    }

    public function jsonDashboardItem(Request $request)
    {
        // 
        $query = $request->get('q');
        $results = Material::get(['id', 'name_item']);
        return response()->json($results, 200);
    }

    public function jsonGraph(Request $req)
    {
        $cust = "";
        if ($req->item_id) {
            $cust .= " AND item_id = $req->item_id ";
        }
        $sql = "WITH RECURSIVE DateRange AS (
                SELECT '$req->startDate' AS Date
                UNION ALL
                SELECT Date + INTERVAL 1 DAY
                FROM DateRange
                WHERE Date < '$req->endDate')
                SELECT Date , coalesce(Y.total_in,0)total_in, coalesce(X.total_out,0)total_out
                FROM DateRange
                left join(
                    select date_format(b.date_trans,'%Y-%m-%d') as dates ,
                    sum((harga_jual * out_stock ) - discount )total_out from tbl_trn_detail_sales a
                    left join tbl_trn_header_trans b on a.header_id = b.id 
                    where b.types in ('sales')
                    $cust
                    GROUP BY b.date_trans
                )X on X.dates = Date 
                left join(
                    select date_format(b.date_trans,'%Y-%m-%d') as dates ,
                    sum(hpp * in_stock)total_in from tbl_trn_detail_beli a
                    left join tbl_trn_header_trans b on a.header_id = b.id 
                    where b.types in ('beli')
                    $cust
                    GROUP BY b.date_trans
                )Y on Y.dates = Date  
                order by Date ASC   ";
        $query = DB::select($sql);
        $data = [];
        $label = ["Pembelian", "Penjualan"];
        $inboundArray = [];
        $outboundArray = [];
        foreach ($query as $q) {
            $inboundArray[] = $q->total_in;
            $outboundArray[] = $q->total_out;
        }

        $data[] = array(
            'label_in' => $label[0],
            'label_out' => $label[1],
            'data_in' => $inboundArray,
            'data_out' => $outboundArray,
        );

        return response()->json($data);
    }
}
