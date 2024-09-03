<?php

use Illuminate\Support\Facades\DB;
use Modules\Administrator\App\Models\Sales;

function getNoTransaksi()
{
    $data =  DB::select("SELECT COALESCE(MAX(CAST(SUBSTRING_INDEX(tts.no_transaksi, '.', -1) AS UNSIGNED)), 0) AS noUrut
FROM tbl_trn_header_trans tts
WHERE tts.type IN ('out'); ");
    $increase = (int) $data[0]->noUrut + 1;
    $str_dn_ = "TRN." . date('myd') . '.' . $increase;
    $DN =  $str_dn_;
    return $DN;

    return null;
}

function getSuratJalanAdjust($idCustomers)
{
    if ($idCustomers) {
        $data =  DB::select("SELECT max(substr(tts.no_transaksi ,12,12)) noUrut  
        from  tbl_mst_header_trans tts Where tts.type in ('out') ");
        $cust  = Sales::find($idCustomers);

        if ($data[0]->noUrut) {
            $increase =  $data[0]->noUrut + 1;
            $str_dn_ = str_pad($increase, 4, 0, STR_PAD_LEFT);
            $DN =  $str_dn_ . '/' . "ADJUST/" . 'RIM/' . $cust->code_customers . '/' . getRomawiMonth((int) date('m')) . '/' . date('Y');
            return $DN;
        } else {
            $DN =  '0001/' . "ADJUST/" . 'RIM/' . $cust->code_customers . '/' . getRomawiMonth((int) date('m')) . '/' . date('Y');
            return $DN;
        }
    }
    return null;
}

function getRomawiMonth($month)
{
    $romawiMonths = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII',
    ];

    return $romawiMonths[$month];
}

function CrudMenuPermission($MenuUrl, $UserId, $act)
{
    $data = DB::select("SELECT `delete` ,`edit`,`add` FROM vw_sys_menu WHERE MenuUrl = '$MenuUrl' and user_id = '$UserId'    ");
    if ($act == "add") {
        if ($data) {
            return $data[0]->add;
        } else {
            return null;
        }
    } else  if ($act == "delete") {
        if ($data) {
            return $data[0]->delete;
        } else {
            return null;
        }
    } else if ($act == "edit") {
        if ($data) {
            return $data[0]->edit;
        } else {
            return null;
        }
    }
}

function terbilang($number)
{
    $words = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");

    if ($number < 12) {
        return " " . $words[$number];
    } elseif ($number < 20) {
        return terbilang($number - 10) . " belas";
    } elseif ($number < 100) {
        return terbilang(floor($number / 10)) . " puluh" . terbilang($number % 10);
    } elseif ($number < 200) {
        return " seratus" . terbilang($number - 100);
    } elseif ($number < 1000) {
        return terbilang(floor($number / 100)) . " ratus" . terbilang($number % 100);
    } elseif ($number < 2000) {
        return " seribu" . terbilang($number - 1000);
    } elseif ($number < 1000000) {
        return terbilang(floor($number / 1000)) . " ribu" . terbilang($number % 1000);
    } elseif ($number < 1000000000) {
        return terbilang(floor($number / 1000000)) . " juta" . terbilang($number % 1000000);
    } elseif ($number < 1000000000000) {
        return terbilang(floor($number / 1000000000)) . " milyar" . terbilang($number % 1000000000);
    } elseif ($number < 1000000000000000) {
        return terbilang(floor($number / 1000000000000)) . " triliun" . terbilang($number % 1000000000000);
    } else {
        return "Angka terlalu besar";
    }
}
