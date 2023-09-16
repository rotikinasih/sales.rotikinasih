<?php

use Illuminate\Support\Facades\Route;

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
Route::prefix('apps')->group(function() {

    //middleware "auth"
    Route::group(['middleware' => ['auth']], function () {

        //route dashboard
        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)->name('apps.dashboard');

        //route pt
        Route::resource('/perusahaan', App\Http\Controllers\Apps\PerusahaanController::class, [
            'names' => [
                'index' => 'apps.perusahaan.index'
            ]
        ]);

        //route divisi
        Route::resource('/divisi', App\Http\Controllers\Apps\DivisiController::class, [
            'names' => [
                'index' => 'apps.divisi.index'
            ]
        ]);

        //route jabatan
        Route::resource('/jabatan', App\Http\Controllers\Apps\JabatanController::class, [
            'names' => [
                'index' => 'apps.jabatan.index'
            ]
        ]);

         //route posisi
        Route::resource('/posisi', App\Http\Controllers\Apps\PosisiController::class, [
            'names' => [
                'index' => 'apps.posisi.index'
            ]
        ]);

        //route karyawan export excel
        Route::get('/karyawan/export', [App\Http\Controllers\Apps\KaryawanController::class, 'export'])->name('apps.karyawan.export');

        //route karyawan import excel
        Route::post('/karyawan/import', [App\Http\Controllers\Apps\KaryawanController::class, 'import'])->name('apps.karyawan.import');

        //route karyawan format excel
        Route::get('/karyawan/format', [App\Http\Controllers\Apps\KaryawanController::class, 'format'])->name('apps.karyawan.format');

        //route karyawan show detail
        Route::get('/karyawan/detail', [App\Http\Controllers\Apps\KaryawanController::class, 'detail'])->name('apps.karyawan.detail');

        //route download pdf detail karyawan
        Route::get('/karyawan/download-pdf/{id}', [App\Http\Controllers\Apps\KaryawanController::class, 'downloadPDF'])->name('apps.karyawan.downloadPDF');

        //route storeKarir
        Route::post('/karyawan/storeKarir', [App\Http\Controllers\Apps\KaryawanController::class, 'storeKarir'])->name('apps.karyawan.storeKarir');

        //route storePelanggaran
        Route::post('/karyawan/storePelanggaran', [App\Http\Controllers\Apps\KaryawanController::class, 'storePelanggaran'])->name('apps.karyawan.storePelanggaran');

        //route listOrganisasi
        Route::get('/karyawan/{id}/list-organisasi', [App\Http\Controllers\Apps\RiwayatOrganisasiController::class, 'index'])->name('apps.organisasi.index');

        //route listOrganisasiAll
        Route::get('/list-organisasi', [App\Http\Controllers\Apps\RiwayatOrganisasiController::class, 'indexAll'])->name('apps.organisasi.indexAll');

        //route update listKarir
        Route::put('/karyawan/{id}/list-organisasi', [App\Http\Controllers\Apps\RiwayatOrganisasiController::class, 'update'])->name('apps.organisasi.update');

        //route organisasi export excel
        Route::get('/list-organisasi/export', [App\Http\Controllers\Apps\RiwayatOrganisasiController::class, 'export'])->name('apps.organisasi.export');

        //route listPelanggaran
        Route::get('/karyawan/{id}/list-pelanggaran', [App\Http\Controllers\Apps\CatatanPelanggaranController::class, 'index'])->name('apps.pelanggaran.index');

        //route listPelanggaranAll
        Route::get('/list-pelanggaran', [App\Http\Controllers\Apps\CatatanPelanggaranController::class, 'indexAll'])->name('apps.pelanggaran.indexAll');

        //route update listPelanggaran
        Route::put('/karyawan/{id}/list-pelanggaran', [App\Http\Controllers\Apps\CatatanPelanggaranController::class, 'update'])->name('apps.pelanggaran.update');

         //route organisasi export excel
        Route::get('/list-pelanggaran/export', [App\Http\Controllers\Apps\CatatanPelanggaranController::class, 'export'])->name('apps.pelanggaran.export');

        //route karyawan
        Route::resource('/karyawan', App\Http\Controllers\Apps\KaryawanController::class, [
            'names' => [
                'index' => 'apps.karyawan.index',
                'create' => 'apps.karyawan.create',
                'edit' => 'apps.karyawan.edit',
                'delete' => 'apps.karyawan.delete'
            ]
        ]);

        //route resign
        Route::resource('/resign', App\Http\Controllers\Apps\ResignController::class, [
            'names' => [
                'index' => 'apps.resign.index'
            ]
        ]);

        //route phk
        Route::resource('/phk', App\Http\Controllers\Apps\PHKController::class, [
            'names' => [
                'index' => 'apps.phk.index'
            ]
        ]);


        //route permission       
        Route::resource('/permissions', App\Http\Controllers\Apps\PermissionController::class, [
            'names' => [
                'index' => 'apps.permissions.index',
            ]
        ]);

        
        //route pelatihan
        Route::resource('/pelatihan', App\Http\Controllers\Apps\PelatihanController::class, [
            'names' => [
                'index' => 'apps.pelatihan.index'
            ]
        ]);


        //route permission
        // Route::get('/permissions', App\Http\Controllers\Apps\PermissionController::class)->name('apps.permissions');

        //route resource roles
        Route::resource('/roles', \App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        //route resource users
        Route::resource('/users', \App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
            ->middleware('permission:users.index|users.create|users.edit|users.delete');

    });
});
