<?php

namespace Modules\Administrator\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Administrator\App\Models\Stock;


class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administrator::stock/index');
    }

    public function jsonSummary(Request $req)
    {
        $response = SummaryStock::jsonSummary($req);
        return response()->json($response);
    }


    function jsonStock(Request $req)
    {
        $response = Stock::jsonList($req);
        return response()->json($response);
    }
}
