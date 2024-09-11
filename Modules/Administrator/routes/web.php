<?php

use Illuminate\Support\Facades\Route;
use Modules\Administrator\App\Http\Controllers\AdministratorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['check.session', 'check.menuAccess'])->prefix('administrator')->group(function () {

    // DASHBOARD ROUTES 
    Route::get('dashboard', 'DashboardController@index');
    Route::get('countMember', 'DashboardController@countMember');
    Route::get('countMaterial', 'DashboardController@countMaterial');
    Route::get('countPenjualan', 'DashboardController@countPenjualan');
    Route::get('countPembelian', 'DashboardController@countPembelian');
    Route::get('countAdjust', 'DashboardController@countAdjust');
    Route::get('jsonGraph', 'DashboardController@jsonGraph');
    Route::get('jsonDashboardItem', 'DashboardController@jsonDashboardItem');

    // UNITS ROUTES 
    Route::get('units', 'UnitsController@index');
    Route::get('jsonUnits', 'UnitsController@jsonUnits');
    Route::post('jsonCreateUnits', 'UnitsController@jsonCreate');
    Route::post('jsonDetailUnits', 'UnitsController@jsonDetail');
    Route::post('jsonUpdateUnits', 'UnitsController@jsonUpdate');
    Route::post('jsonDeleteUnits', 'UnitsController@jsonDelete');


    // MEMBER ROUTES 
    Route::get('member', 'MemberController@index');
    Route::get('jsonMember', 'MemberController@jsonMember');
    Route::post('jsonCreateMember', 'MemberController@jsonCreate');
    Route::post('jsonDetailMember', 'MemberController@jsonDetail');
    Route::post('jsonUpdateMember', 'MemberController@jsonUpdate');
    Route::post('jsonDeleteMember', 'MemberController@jsonDelete');

    // LEVEL MEMBER ROUTES 
    Route::get('levelmember', 'LevelMemberController@index');
    Route::get('jsonLevelMember', 'LevelMemberController@jsonLevelMember');
    Route::post('jsonCreateLevelMember', 'LevelMemberController@jsonCreate');
    Route::post('jsonDetailLevelMember', 'LevelMemberController@jsonDetail');
    Route::post('jsonUpdateLevelMember', 'LevelMemberController@jsonUpdate');
    Route::post('jsonDeleteLevelMember', 'LevelMemberController@jsonDelete');



    // MATERIAL ROUTES 
    Route::get('material', 'MaterialController@index');
    Route::get('jsonMaterial', 'MaterialController@jsonMaterial');
    Route::post('jsonCreateMaterial', 'MaterialController@jsonCreate');
    Route::post('jsonDetailMaterial', 'MaterialController@jsonDetail');
    Route::post('jsonUpdateMaterial', 'MaterialController@jsonUpdate');
    Route::post('jsonDeleteMaterial', 'MaterialController@jsonDelete');
    Route::get('jsonLocationMaterialByWarehouse', 'MaterialController@jsonLocation');
    Route::post('uploadItemExcel', 'MaterialController@uploadItemExcel');
    Route::get('barcodeGenerate', 'MaterialController@barcodeGenerate');



    // PRICE ROUTES 
    Route::get('jsonMaterialPrice', 'MaterialController@jsonDetailPrice');
    Route::post('jsonCreatePrice', 'MaterialController@jsonCreatePrice');
    Route::post('jsonUpdatePrice', 'MaterialController@jsonUpdatePrice');
    Route::post('jsonDeletePrice', 'MaterialController@jsonDeletePrice');
    Route::post('uploadHargaExcel', 'MaterialController@uploadHargaExcel');

    //  WAREHOUSE  ROUTES
    Route::get('warehouse', 'WarehouseController@index');
    Route::get('jsonWarehouse', 'WarehouseController@jsonWarehouse');
    Route::post('jsonCreateWarehouse', 'WarehouseController@jsonCreate');
    Route::post('jsonDetailWarehouse', 'WarehouseController@jsonDetail');
    Route::post('jsonUpdateWarehouse', 'WarehouseController@jsonUpdate');
    Route::post('jsonDeleteWarehouse', 'WarehouseController@jsonDelete');

    //  CATEGORY  ROUTES
    Route::get('category', 'CategoryController@index');
    Route::get('jsonCategory', 'CategoryController@jsonCategory');
    Route::post('jsonCreateCategory', 'CategoryController@jsonCreate');
    Route::post('jsonDetailCategory', 'CategoryController@jsonDetail');
    Route::post('jsonUpdateCategory', 'CategoryController@jsonUpdate');
    Route::post('jsonDeleteCategory', 'CategoryController@jsonDelete');

    //  PAJAK  ROUTES
    Route::get('pajak', 'PajakController@index');
    Route::get('jsonPajak', 'PajakController@jsonPajak');
    Route::post('jsonCreatePajak', 'PajakController@jsonCreate');
    Route::post('jsonDetailPajak', 'PajakController@jsonDetail');
    Route::post('jsonUpdatePajak', 'PajakController@jsonUpdate');
    Route::post('jsonDeletePajak', 'PajakController@jsonDelete');


    // LOCATION ROUTES 
    Route::get('location', 'LocationController@index');
    Route::get('jsonLocation', 'LocationController@jsonLocation');
    Route::post('jsonCreateLocation', 'LocationController@jsonCreate');
    Route::post('jsonDetailLocation', 'LocationController@jsonDetail');
    Route::post('jsonUpdateLocation', 'LocationController@jsonUpdate');
    Route::post('jsonDeleteLocation', 'LocationController@jsonDelete');
    Route::get('jsonForListLocation', 'LocationController@jsonForListLocation');



    // ROLES ROUTES 
    Route::get('roles', 'RolesController@index');
    Route::get('jsonRole', 'RolesController@jsonRole');
    Route::get('jsonDetailListMenu', 'RolesController@jsonDetailListMenu');
    Route::post('jsonCreateRoles', 'RolesController@jsonCreate');
    Route::post('jsonDeleteRoles', 'RolesController@jsonDelete');
    Route::post('jsonUpdateRoles', 'RolesController@jsonUpdate');
    Route::get('jsonForListRoles', 'RolesController@jsonForListRoles');


    // USERS ROUTES 
    Route::get('users', 'UsersController@index');
    Route::get('jsonUsers', 'UsersController@jsonUsers');
    Route::get('jsonListMenuForUsers', 'UsersController@jsonListMenuForUsers');
    Route::post('jsonCreateUsers', 'UsersController@jsonCreate');
    Route::post('jsonDeleteUsers', 'UsersController@jsonDelete');
    Route::post('jsonUpdateUsers', 'UsersController@jsonUpdate');

    // SALES ROUTES 
    Route::get('penjualan', 'SalesController@index');
    Route::get('jsonSales', 'SalesController@jsonSales');
    Route::get('jsonDetailSales', 'SalesController@jsonDetailSales');
    Route::get('getPrice', 'SalesController@getJsonPrice');
    Route::post('jsonSaveTransaksi', 'SalesController@jsonSaveTransaksi');
    Route::post('jsonCancelTransaksi', 'SalesController@jsonCancelTransaksi');
    Route::get('jsonDeleteSales', 'SalesController@jsonDeleteSales');
    Route::get('jsonPrintStruck', 'SalesController@jsonPrintStruck');
    Route::get('jsonPrintInvoice', 'SalesController@jsonPrintInvoice');
    Route::get('jsonNoTransaksi', 'SalesController@jsonNoTransaksi');
    Route::get('jsonDetailSalesEdit', 'SalesController@jsonDetailSalesEdit');

    // ADJUST ROUTES 
    Route::get('adjust', 'AdjustController@index');
    Route::get('jsonAdjust', 'AdjustController@jsonAdjust');
    Route::get('jsonNoTransaksiAdjust', 'AdjustController@jsonNoTransaksiAdjust');
    Route::post('jsonSaveTransaksiAdjust', 'AdjustController@jsonSaveTransaksiAdjust');
    Route::get('jsonDeleteAdjust', 'AdjustController@jsonDeleteAdjust');
    Route::get('jsonDetailAdjust', 'AdjustController@jsonDetailAdjust');

    // PEMBELIAN ROUTES 
    Route::get('pembelian', 'PembelianController@index');
    Route::get('jsonPembelian', 'PembelianController@jsonPembelian');
    Route::get('jsonListDetailBeli', 'PembelianController@jsonListDetailBeli');
    Route::get('getJsonPriceBeli', 'PembelianController@getJsonPriceBeli');
    Route::get('jsonDeleteBeli', 'PembelianController@jsonDeleteBeli');
    Route::post('jsonSaveTransaksiBeli', 'PembelianController@jsonSaveTransaksiBeli');
    Route::get('jsonNoTransaksiBeli', 'PembelianController@jsonNoTransaksiBeli');
    Route::get('jsonListDetailBeliEdit', 'PembelianController@jsonListDetailBeliEdit');

    // STOCK ROUTES 
    Route::get('stock', 'StockController@index');
    Route::get('jsonStock', 'StockController@jsonStock');


    // REPORTING
    Route::get('reportOut', 'ReportingController@reportOut');
    Route::get('reportIn', 'ReportingController@reportIn');
    Route::get('jsonListItemReporting', 'ReportingController@jsonListItemReporting');
    Route::get('jsonListItemReporting', 'ReportingController@jsonListItemReporting');
    Route::get('exportReportSales', 'ReportingController@exportReportSales');
    Route::get('exportReportBeli', 'ReportingController@exportReportBeli');



    // CMS ROUTES 
    Route::get('cms', 'CmsController@index');
    Route::post('updateCms', 'CmsController@updateCms');
});
