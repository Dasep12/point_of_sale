<?php

namespace Modules\Administrator\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;


class Adjust extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'tbl_trn_header_trans';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_transaksi',
        'type',
        'types',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];
    public static function jsonList($req)
    {


        $page = $req->input('page'); // current page number
        $limit = $req->input('rows'); // rows per page
        $sidx = $req->input('sidx'); // sort column
        $sord = $req->input('sord'); // sort direction

        $query = DB::table('tbl_trn_header_trans as a')
            ->select('a.id', 'a.type', 'a.date_trans', 'a.no_transaksi', 'a.status_bayar', 'a.created_by', DB::raw('COUNT(b.id) as total_item'))
            ->leftJoin('tbl_trn_detail_sales as b', 'b.header_id', '=', 'a.id')
            ->groupBy('a.id', 'a.type', 'a.date_trans', 'a.no_transaksi', 'a.status_bayar', 'a.created_by')
            ->orderByDesc('a.id');

        $count = $query->count();

        $data = $query->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalPages = ($count > 0) ? ceil($count / $limit) : 0;

        $response = [
            'page' => $page,
            'total' => $totalPages,
            'records' => $count,
            'rows' => $data->toArray(),
        ];

        return $response;
    }

    public static function jsonListDetailAdjust($req)
    {

        $page = $req->input('page'); // current page number
        $limit = $req->input('rows'); // rows per page
        $sidx = $req->input('sidx'); // sort column
        $sord = $req->input('sord'); // sort direction

        if ($req->adjust_type == "in") {
            $query = DB::table('tbl_trn_detail_beli as a')
                ->where('header_id', $req->id)
                ->select('a.item_name', 'a.unit_name', 'a.in_stock as qty');
        } else {
            $query = DB::table('tbl_trn_detail_sales as a')
                ->where('header_id', $req->id)
                ->select('a.item_name', 'a.unit_name', 'a.out_stock as qty');
        }




        $count = $query->count();

        $data = $query->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();

        $totalPages = ($count > 0) ? ceil($count / $limit) : 0;

        $response = [
            'page' => $page,
            'total' => $totalPages,
            'records' => $count,
            'rows' => $data->toArray(),
        ];

        return $response;
    }
}
