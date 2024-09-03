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
use Modules\Administrator\App\Models\Material;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::dashboard/index');
    }

    public function countCustomers()
    {
        if (session()->get("customers_id") != "*") {
            $data = Customers::where('id', session()->get("customers_id"))->count();
        } else {
            $data = Customers::count();
        }
        return response()->json(['data' => $data]);
    }

    public function countMaterial()
    {
        if (session()->get("customers_id") != "*") {
            $data = Material::where('customers_id', session()->get("customers_id"))->count();
        } else {
            $data = Material::count();
        }
        return response()->json(['data' => $data]);
    }

    public function countInbound()
    {
        if (session()->get("customers_id") != "*") {
            $data = Inbound::where(['customer_id' => session()->get("customers_id"), 'types' => 'in', 'types_trans' => 'Order'])->count();
        } else {
            $data = Inbound::where(['types' => 'in', 'types_trans' => 'Order'])->count();
        }
        return response()->json(['data' => $data]);
    }

    public function countOutbound()
    {
        if (session()->get("customers_id") != "*") {
            $data = Inbound::where(['customer_id' => session()->get("customers_id"), 'types' => 'out', 'types_trans' => 'Order'])->count();
        } else {
            $data = Inbound::where(['types' => 'out', 'types_trans' => 'Order'])->count();
        }
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

    public function jsonGraph(Request $req)
    {
        $cust = "";
        if ($req->customers) {
            $cust .= " AND customer_id = $req->customers ";
        }
        $sql = "WITH RECURSIVE DateRange AS (
                SELECT '$req->startDate' AS Date
                UNION ALL
                SELECT Date + INTERVAL 1 DAY
                FROM DateRange
                WHERE Date < '$req->endDate')
                SELECT Date , coalesce(X.res,0)total_in,coalesce(Y.res,0)total_out
                FROM DateRange
                left join(
                    select count(id) res,date_format(date_trans,'%Y-%m-%d')dates 
                    from tbl_trn_shipingmaterial 
                    where types in ('in') and types_trans in ('Order')
                    $cust 
                    group by types, types_trans , date_trans
                )X on X.dates = Date 
                left join(
                    select count(id) res,date_format(date_trans,'%Y-%m-%d')dates 
                    from tbl_trn_shipingmaterial 
                    where types in ('out') and types_trans in ('Order')
                    $cust 
                    group by types, types_trans , date_trans
                )Y on X.dates = Date 
                order by Date ASC   ";
        $query = DB::select($sql);
        $data = [];
        $label = ["Inbound", "Outbound"];
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
