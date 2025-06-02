<?php

use App\Models\Menu;
use Faker\Extension\Helper;
use App\Events\ServerCreated;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FpbController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GitLogController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\UserWifiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\KeteranganController;
use App\Http\Controllers\ReportDataUserController;
use App\Http\Controllers\ReportJumlahUserController;



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
// Route::get('/coba', [CobaController::class, 'index'])->name('coba');


// data dashboard
Route::get('/data-dashboard', [DashboardController::class, 'index'])->name('data-dashboard')->middleware('auth')->middleware('cekmenuakses');
Route::post('load-grafik-jml-barang-operasional', [DashboardController::class, 'loadGrafikJmlBarangOperasional'])->name('load-grafik-jml-barang-operasional')->middleware('auth');
Route::get('/git-log', [GitLogController::class, 'index'])->name('git-log')->middleware('auth');

Route::post('tampil-halaman-chart-satu', [DashboardController::class, 'tampilHalamanChartSatu'])->name('tampil-halaman-chart-satu')->middleware('auth');
Route::post('load-chart-satu', [DashboardController::class, 'loadChartSatu'])->name('load-chart-satu')->middleware('auth');
// 


// == master 
// master keterangan
Route::get('/keterangan', [KeteranganController::class, 'index'])->name('keterangan')->middleware('auth');
Route::post('load-table-keterangan', [KeteranganController::class, 'loadTableKeterangan'])->name('load-table-keterangan')->middleware('auth');
Route::post('store-keterangan', [KeteranganController::class, 'store'])->middleware('auth');
Route::post('destroy-keterangan', [KeteranganController::class, 'destroy'])->middleware('auth');

// user wifi
Route::get('/user-wifi', [UserWifiController::class, 'index'])->name('user-wifi')->middleware('auth');
Route::post('load-table-user-wifi', [UserWifiController::class, 'loadTableUserWifi'])->name('load-table-user-wifi')->middleware('auth');
Route::post('load-kode-pegawai', [UserWifiController::class, 'loadKodePegawai'])->name('load-kode-pegawai')->middleware('auth');
Route::post('form-save-user-wifi', [UserWifiController::class, 'saveUserWifi'])->name('form-save-user-wifi')->middleware('auth');
Route::post('destroy-user-wifi', [UserWifiController::class, 'destroyUserWifi'])->name('destroy-user-wifi')->middleware('auth');
Route::post('status-user-wifi', [UserWifiController::class, 'statusUserWifi'])->name('status-user-wifi')->middleware('auth');
Route::get('/tampil-surat/{id}', [UserWifiController::class, 'tampilSurat'])->name('tampil-surat');
Route::get('/tampil-surat-dev/{id}', [UserWifiController::class, 'tampilSuratDev'])->name('tampil-surat-dev');
Route::post('kirim-surat-pengajuan-user-wifi', [UserWifiController::class, 'kirimSuratPengajuanUserWifi'])->name('kirim-surat-pengajuan-user-wifi')->middleware('auth');
Route::post('kirim-user-pengajuan-user-wifi', [UserWifiController::class, 'kirimUserPengajuanUserWifi'])->name('kirim-user-pengajuan-user-wifi')->middleware('auth');

// user-asset
Route::get('/user-asset', [UserAssetController::class, 'index'])->name('user-asset')->middleware('auth');
Route::post('load-table-user-asset', [UserAssetController::class, 'loadTableUserAsset'])->name('load-table-user-asset')->middleware('auth');
Route::post('form-save-user-asset', [UserAssetController::class, 'saveUserAsset'])->name('form-save-user-asset')->middleware('auth');
Route::post('destroy-user-asset', [UserAssetController::class, 'destroyUserAsset'])->name('destroy-user-asset')->middleware('auth');
Route::post('status-user-asset', [UserAssetController::class, 'statusUserAsset'])->name('status-user-asset')->middleware('auth');

// report-data-user
Route::get('/report-data-user', [ReportDataUserController::class, 'index'])->name('report-data-user')->middleware('auth');
Route::post('load-table-report-data-user', [ReportDataUserController::class, 'loadTableReportDataUser'])->name('load-table-report-data-user')->middleware('auth');

// report-jumlah-user
Route::get('/report-jumlah-user', [ReportJumlahUserController::class, 'index'])->name('report-jumlah-user')->middleware('auth');
Route::post('load-tampil-data-report-jumlah-user', [ReportJumlahUserController::class, 'loadTampilDataReportJumlahUser'])->name('load-tampil-data-report-jumlah-user')->middleware('auth');
Route::post('load-table-detail-data-user', [ReportJumlahUserController::class, 'loadTableDetailDataUser'])->name('load-table-detail-data-user')->middleware('auth');
Route::post('load-table-detail-data-asset', [ReportJumlahUserController::class, 'loadTableDetailDataAsset'])->name('load-table-detail-data-asset')->middleware('auth');


// select
Route::post('get-jenis-perangkat', [SelectController::class, 'getJenisPerangkat'])->name('get-jenis-perangkat')->middleware('auth');
Route::post('get-nama-pegawai', [SelectController::class, 'getNamaPegawai'])->name('get-nama-pegawai')->middleware('auth');
Route::post('get-anggaran-rab', [SelectController::class, 'getAnggaranRab'])->name('get-anggaran-rab')->middleware('auth');
Route::post('get-anggaran-rab-detail', [SelectController::class, 'getAnggaranRabDetail'])->name('get-anggaran-rab-detail')->middleware('auth');

// select
Route::post('/select-nama-pegawai', [SelectController::class, 'namaPegawai'])->name('nama-pegawai')->middleware('auth');
Route::post('/select-nama-asset', [SelectController::class, 'namaAsset'])->name('select-nama-asset')->middleware('auth');



// login
Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// regis
Route::get('/registrasi-awal', [RegistrasiAwalController::class, 'index'])->name('registrasi-awal')->middleware('auth');
Route::post('admin/load-tabel-registrasi-awal', [RegistrasiAwalController::class, 'loadTabelRegistrasiAwal'])->name('load-tabel-registrasi-awal')->middleware('admin');

// level
Route::get('/admin/level', [LevelController::class, 'index'])->middleware('auth');
Route::post('admin/load-tabel-level', [LevelController::class, 'loadTabelLevel'])->name('load-tabel-level');
Route::post('admin/store-level', [LevelController::class, 'store']);
Route::post('admin/destroy-level', [LevelController::class, 'destroy']);

// user
Route::get('/admin/user', [UserController::class, 'index'])->middleware('auth');
Route::post('admin/load-tabel-user', [UserController::class, 'loadTabelUser'])->name('load-tabel-user');
Route::post('admin/hapus-akses', [UserController::class, 'hapusAkses']);
Route::post('admin/simpan-level-user', [UserController::class, 'simpanLevelUser']);
Route::post('admin/simpan-bidang-user', [UserController::class, 'simpanBidangUser']);

// menu header
Route::get('/admin/menu', [MenuController::class, 'index'])->middleware('auth');
Route::post('admin/load-tabel-menu-header', [MenuController::class, 'loadTabelMenuHeader'])->name('load-tabel-menu-header');
Route::post('admin/store-menu-header', [MenuController::class, 'storeMenuHeader']);
Route::post('admin/detail-menu-header', [MenuController::class, 'detailMenuHeader']);
// menu parent
Route::post('admin/load-tabel-menu-parent', [MenuController::class, 'loadTabelMenuParent'])->name('load-tabel-menu-parent');
Route::post('admin/store-menu-parent', [MenuController::class, 'storeMenuParent']);
Route::post('admin/destroy-menu-parent', [MenuController::class, 'destroyParent']);

// akses
Route::get('/admin/akses', [AksesController::class, 'index'])->middleware('auth');
Route::post('admin/tampil-level-akses', [AksesController::class, 'tampilLevelAkses'])->name('tampil-level-akses');
Route::post('admin/simpan-akses-menu', [AksesController::class, 'simpanAksesMenu']);

