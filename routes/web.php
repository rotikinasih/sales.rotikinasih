<?php

use App\Http\Controllers\Apps\DistribusiProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\KasirController;
use App\Http\Controllers\Apps\MasterKendaraanController;
use App\Http\Controllers\Apps\MonitoringOrderController;
use App\Http\Controllers\Apps\OrderPenjualanController;
use App\Http\Controllers\Apps\MonitoringStokController;
use App\Http\Controllers\Apps\ReturProdukController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

//route home
Route::get('/', function () {
    return \Inertia\Inertia::render('Auth/Login');
})->middleware('guest');

//prefix "apps"
Route::prefix('apps')->group(function () {
    Route::group(['middleware' => ['auth']], function () {
        //route dashboard
        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');


        //route pt
        Route::resource('/orderpenjualan', App\Http\Controllers\Apps\OrderPenjualanController::class, [
            'names' => [
                'index' => 'apps.orderpenjualan.index',
            ],
        ]);
        Route::post('orderpenjualan/{id}/cicilan', [OrderPenjualanController::class, 'cicilan']);

        //route produk
        Route::resource('/produk', App\Http\Controllers\Apps\MasterProdukController::class, [
            'names' => [
                'index' => 'apps.produk.index',
            ],
        ]);

        //route katproduk
        Route::resource('/katproduk', App\Http\Controllers\Apps\KategoriProdukController::class, [
            'names' => [
                'index' => 'apps.katproduk.index',
            ],
        ]);

        //route tipe outlet
        Route::resource('/toutlet', App\Http\Controllers\Apps\TipeOutletController::class, [
            'names' => [
                'index' => 'apps.toutlet.index',
            ],
        ]);
        Route::get('/kasir/download-nota/{transaksi_id}', [KasirController::class, 'downloadNota'])->name('kasir.downloadNota');
Route::get('/kasir/print-nota/{transaksi_id}', [KasirController::class, 'printNota'])->name('kasir.printNota');


        // Route kasir lengkap
        Route::prefix('/kasir')->name('kasir.')->group(function () {
            Route::get('/', [KasirController::class, 'index'])->name('index');
            Route::post('/tambah-stok', [KasirController::class, 'tambahStok'])->name('tambah-stok');
            Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
            Route::post('/edit-stok', [KasirController::class, 'editStok'])->name('edit-stok');
            Route::get('/rekap', [KasirController::class, 'rekap'])->name('rekap');
            Route::get('/export', [KasirController::class, 'export'])->name('export');
            Route::delete('/rekap/{id}', [KasirController::class, 'destroyTransaksi'])->name('rekap.delete');
        });

        Route::get('/order-produksi-kasir', [OrderPenjualanController::class, 'orderProduksiKasir'])->name('apps.orderproduksi.kasir');
        Route::post('/order-produksi-kasir', [OrderPenjualanController::class, 'storeOrderProduksiKasir'])->name('apps.orderproduksi.kasir.store');



        //route Master Outlet
        Route::resource('/outlet', App\Http\Controllers\Apps\MasterOutletController::class, [
            'names' => [
                'index' => 'apps.outlet.index',
            ],
        ]);

        //route permission
        Route::resource('/permissions', App\Http\Controllers\Apps\PermissionController::class, [
            'names' => [
                'index' => 'apps.permissions.index',
            ],
        ]);

        //route pelatihan


        // Monitoring Stok
        Route::get('/monitoring-stok/export', [MonitoringStokController::class, 'export'])
            ->name('apps.monitoringstok.export');

        Route::get('/monitoring-stok/print', [MonitoringStokController::class, 'print'])
            ->name('apps.monitoringstok.print');

        Route::resource('/monitoring-stok', MonitoringStokController::class, [
            'names' => [
                'index' => 'apps.monitoringstok.index',
            ],
        ]);

        Route::get('/distribusi-produk/print-harian', [DistribusiProdukController::class, 'printHarian'])->name('apps.distribusiproduk.printHarian');
Route::get('/distribusi-produk/export-harian', [DistribusiProdukController::class, 'exportHarian'])->name('apps.distribusiproduk.exportHarian');

        Route::resource('/distribusi-produk', App\Http\Controllers\Apps\DistribusiProdukController::class, [
            'names' => [
                'index' => 'apps.distribusiproduk.index',
                'store' => 'apps.distribusiproduk.store',
                'update' => 'apps.distribusiproduk.update',
            ],
        ]);

        Route::get('/distribusi-produk/{orderId}/export-surat-jalan', [App\Http\Controllers\Apps\DistribusiProdukController::class, 'exportSuratJalan'])
            ->name('apps.distribusiproduk.exportSuratJalan');

        Route::get('/print/surat-jalan/{orderId}', [DistribusiProdukController::class, 'printSuratJalan'])->name('surat-jalan.print');

        Route::get('/list-purchase-order', [\App\Http\Controllers\Apps\DistribusiProdukController::class, 'listPurchaseOrder'])->name('apps.listpurchaseorder.index');


        //route master kendaraan
        Route::resource('/masterkendaraan', MasterKendaraanController::class, [
            'names' => [
                'index' => 'apps.masterkendaraan.index',
                // ...
            ],
        ]);


        //route permission
        // Route::get('/permissions', App\Http\Controllers\Apps\PermissionController::class)->name('apps.permissions');

        //route resource roles
        Route::resource('/roles', \App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
            ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        //route resource users
        Route::resource('/users', \App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

        //route monitoring order
        Route::get('/monitoringorder/print', [MonitoringOrderController::class, 'print'])
            ->name('apps.monitoringorder.print');

        Route::get('/monitoringorder/export', [MonitoringOrderController::class, 'export'])
            ->name('apps.monitoringorder.export');



        Route::resource('/monitoringorder', App\Http\Controllers\Apps\MonitoringOrderController::class, [
            'names' => [
                'index' => 'apps.monitoringorder.index',
            ],
        ]);


        // Konfirmasi Distribusi Kasir (pindahkan ke sini)
        Route::get('/konfirmasi-distribusi-kasir', [\App\Http\Controllers\Apps\KonfirmasiDistribusiKasirController::class, 'index'])->name('apps.konfirmasi-distribusi-kasir.index');
        Route::put('/konfirmasi-distribusi-kasir/{id}', [\App\Http\Controllers\Apps\KonfirmasiDistribusiKasirController::class, 'update'])->name('apps.konfirmasi-distribusi-kasir.update');


        //route retur produk
        Route::resource('/returproduk', ReturProdukController::class, [
            'names' => [
                'index' => 'apps.retur.index',
                // ...
            ],
        ]);

        Route::get('/retur-produk/cetak-pdf', [ReturProdukController::class, 'cetakPdf'])->name('retur.cetakPdf');
        Route::get('/retur-produk/export-excel', [ReturProdukController::class, 'exportExcel'])->name('retur.exportExcel');
        Route::get('/retur-produk/print', [ReturProdukController::class, 'print'])->name('retur.print');
        Route::post('/retur-produk/store', [ReturProdukController::class, 'store'])->name('retur.store');
        Route::post('/retur-produk/store-multi', [ReturProdukController::class, 'storeMulti'])->name('retur.storeMulti');
        Route::get('/retur-produk', [ReturProdukController::class, 'index'])->name('retur.index');
        Route::delete('/returproduk/{id}', [ReturProdukController::class, 'destroy'])->name('returproduk.delete');


        Route::get('/laporan-keuangan-harian', [\App\Http\Controllers\Apps\LaporanKeuanganController::class, 'index'])->name('apps.laporankeuangan.index');
        Route::get('/laporan-keuangan-harian/export', [\App\Http\Controllers\Apps\LaporanKeuanganController::class, 'export'])
            ->name('apps.laporankeuangan.export');
        Route::get('/laporan-keuangan-harian/print', [\App\Http\Controllers\Apps\LaporanKeuanganController::class, 'print'])
            ->name('apps.laporankeuangan.print');

            Route::resource('/mastercs', \App\Http\Controllers\Apps\MasterCSController::class, [
    'names' => [
        'index' => 'apps.mastercs.index',
    ],
]);
    });
});

