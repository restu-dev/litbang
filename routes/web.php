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
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\GitLogController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\UserWifiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserAssetController;
use App\Http\Controllers\KeteranganController;
use App\Http\Controllers\JurnalHarianController;
use App\Http\Controllers\MasterBidangController;
use App\Http\Controllers\ReportDataUserController;
use App\Http\Controllers\UserNonCivitasController;
use App\Http\Controllers\ReportKehadiranController;
use App\Http\Controllers\ReportJumlahUserController;
use App\Http\Controllers\ReportJurnalHarianController;
use App\Http\Controllers\MasterJenisKegiatanController;
use App\Http\Controllers\ProgramKerjaTahunanController;
use App\Http\Controllers\MasterTahunPelajaranController;
use App\Http\Controllers\MasterStatusPencapaianController;
use App\Http\Controllers\MasterMappingUserAgendaController;
use App\Http\Controllers\ReportProgramKerjaTahunanController;



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
Route::get('/data-dashboard', [DashboardController::class, 'index'])->name('data-dashboard')->middleware('auth.web_or_admin')->middleware('cekmenuakses');

// dashboard-tampil-data-absensi
Route::post('dashboard-tampil-data-absensi', [DashboardController::class, 'dashboardTampilDataAbsensi'])->name('dashboard-tampil-data-absens')->middleware('auth.web_or_admin');
// dashboard-tampil-data-aktivitas
Route::post('dashboard-tampil-data-aktivitas', [DashboardController::class, 'dashboardTampilDataAktivitas'])->name('dashboard-tampil-data-aktivitas')->middleware('auth.web_or_admin');
// dashboard-tampil-data-agenda
Route::post('dashboard-tampil-data-agenda', [DashboardController::class, 'dashboardTampilDataAgenda'])->name('dashboard-tampil-data-agenda')->middleware('auth.web_or_admin');
// dashboard-tampil-data-chart-jml-aktivitas
Route::post('dashboard-tampil-data-chart-jml-aktivitas', [DashboardController::class, 'dashboardTampilDataChartJmlAktivitas'])->name('dashboard-tampil-data-chart-jml-aktivitas')->middleware('auth.web_or_admin');
Route::post('chart-jml-aktivitas', [DashboardController::class, 'chartJmlAktivitas'])->name('chart-jml-aktivitas')->middleware('auth.web_or_admin');


Route::post('load-grafik-jml-barang-operasional', [DashboardController::class, 'loadGrafikJmlBarangOperasional'])->name('load-grafik-jml-barang-operasional')->middleware('auth.web_or_admin');
Route::get('/git-log', [GitLogController::class, 'index'])->name('git-log')->middleware('auth.web_or_admin');

Route::post('tampil-halaman-chart-satu', [DashboardController::class, 'tampilHalamanChartSatu'])->name('tampil-halaman-chart-satu')->middleware('auth.web_or_admin');
Route::post('load-chart-satu', [DashboardController::class, 'loadChartSatu'])->name('load-chart-satu')->middleware('auth.web_or_admin');

// ganti-password
Route::get('/ubah-password', [DashboardController::class, 'ubahPassword'])->middleware('auth.web_or_admin');
Route::post('/simpan-ubah-password', [DashboardController::class, 'simpanUbahPassword'])->middleware('auth.web_or_admin');


// == kerja

// program-kerja-tahunan
Route::get('/program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'loadTabelProgramKerjaTahunan'])->name('load-tabel-program-kerja-tahunan');
Route::post('simpan-program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'simpanProgramKerjaTahunan']);
Route::post('get-data-edit-program-kerja-tahunan-by-id', [ProgramKerjaTahunanController::class, 'getDataEditProgramKerjaTahunanById']);
Route::post('destroy-program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'destroy']);
Route::post('clone-program-kerja', [ProgramKerjaTahunanController::class, 'cloneDariTahunLalu']);
// impor
Route::get('/download-template-impor-program-kerja', [ProgramKerjaTahunanController::class, 'downloadTemplateImporProgramKerja'])->name('download-template-impor-program-kerja')->middleware('auth.web_or_admin');;
Route::post('import-preview-program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'importPreviewProgramKerjaTahunan'])->middleware('auth.web_or_admin');
Route::post('proses-imports-program-kerja-tahunan', [ProgramKerjaTahunanController::class, 'prosesImportsProgramKerjaTahunan'])->middleware('auth.web_or_admin');


// approval
// Route::get('/coba', [ApprovalController::class, 'coba'])->middleware('auth.web_or_admin');
Route::get('/approval', [ApprovalController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-approval', [ApprovalController::class, 'loadTabelProgramKerjaTahunanApprov'])->name('load-tabel-approval');
Route::post('simpan-approval', [ApprovalController::class, 'simpanApproval']);

// jurnal-harian
Route::get('/jurnal-harian', [JurnalHarianController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-jurnal-harian', [JurnalHarianController::class, 'loadTabelJurnalHarian'])->name('load-tabel-jurnal-harian');
Route::post('simpan-jurnal-harian', [JurnalHarianController::class, 'simpanJurnalHarian']);
Route::post('get-data-edit-jurnal-harian-by-id', [JurnalHarianController::class, 'getDataEditJurnalHarianById']);
Route::post('destroy-jurnal-harian', [JurnalHarianController::class, 'destroy']);

Route::post('preview-dokumen', [JurnalHarianController::class, 'previewDokumen']);

// == agenda

// agenda
Route::get('/agenda', [AgendaController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::get('/agenda-events', [AgendaController::class, 'events'])->middleware('auth.web_or_admin');
Route::post('/agenda/store', [AgendaController::class, 'store'])->middleware('auth.web_or_admin');
Route::get('/agenda/{id}', [AgendaController::class, 'show'])->middleware('auth.web_or_admin');
Route::post('/agenda/update/{id}', [AgendaController::class, 'update'])->middleware('auth.web_or_admin');
Route::post('/agenda/delete/{id}', [AgendaController::class, 'destroy'])->middleware('auth.web_or_admin');
Route::post('/agenda/list-agenda', [AgendaController::class, 'listAgenda'])->middleware('auth.web_or_admin');


// == reporting

// report-program-tahunan
Route::get('/report-program-tahunan', [ReportProgramKerjaTahunanController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-report-program-tahunan', [ReportProgramKerjaTahunanController::class, 'loadTabelReportProgramKerjaTahunan'])->name('load-tabel-report-program-tahunan');

// report-jurnal-harian
Route::get('/report-jurnal-harian', [ReportJurnalHarianController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-report-jurnal-harian', [ReportJurnalHarianController::class, 'loadTabelReportJurnalHarian'])->name('load-tabel-report-jurnal-harian');

// report-kehadiaran
Route::get('/report-kehadiaran', [ReportKehadiranController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-report-kehadiaran', [ReportKehadiranController::class, 'loadTabelReportKehadiran'])->name('load-tabel-report-kehadiaran');


// == master 

// master-tahun-pelajaran
Route::get('/master-tahun-pelajaran', [MasterTahunPelajaranController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-master-tahun-pelajaran', [MasterTahunPelajaranController::class, 'loadTabelMasterTahunPelajaran'])->name('load-tabel-master-tahun-pelajaran');
Route::post('store-master-tahun-pelajaran', [MasterTahunPelajaranController::class, 'store']);
Route::post('destroy-master-tahun-pelajaran', [MasterTahunPelajaranController::class, 'destroy']);

// master_bidang
Route::get('/master-bidang', [MasterBidangController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-master-bidang', [MasterBidangController::class, 'loadTabelMasterBidang'])->name('load-tabel-master-bidang');
Route::post('store-master-bidang', [MasterBidangController::class, 'store']);
Route::post('destroy-master-bidang', [MasterBidangController::class, 'destroy']);

// master-jenis-kegiatan
Route::get('/master-jenis-kegiatan', [MasterJenisKegiatanController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-master-jenis-kegiatan', [MasterJenisKegiatanController::class, 'loadTabelMasterJenisKegiatan'])->name('load-tabel-master-jenis-kegiatan');
Route::post('store-master-jenis-kegiatan', [MasterJenisKegiatanController::class, 'store']);
Route::post('destroy-master-jenis-kegiatan', [MasterJenisKegiatanController::class, 'destroy']);

// master-status-pencapaian
Route::get('/master-status-pencapaian', [MasterStatusPencapaianController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-master-status-pencapaian', [MasterStatusPencapaianController::class, 'loadTabelMasterStatusPencapaian'])->name('load-tabel-master-status-pencapaian');
Route::post('store-master-status-pencapaian', [MasterStatusPencapaianController::class, 'store']);
Route::post('destroy-master-status-pencapaian', [MasterStatusPencapaianController::class, 'destroy']);

// master-mapping-user-agenda
Route::get('/master-mapping-user-agenda', [MasterMappingUserAgendaController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');
Route::post('load-tabel-master-mapping-user-agenda', [MasterMappingUserAgendaController::class, 'loadTabelMasterMappingUserAgenda'])->name('load-tabel-master-mapping-user-agenda');
Route::post('store-master-mapping-user-agenda', [MasterMappingUserAgendaController::class, 'store']);
Route::post('destroy-master-mapping-user-agenda', [MasterMappingUserAgendaController::class, 'destroy']);


// master keterangan
Route::get('/keterangan', [KeteranganController::class, 'index'])->name('keterangan')->middleware('auth.web_or_admin');
Route::post('load-table-keterangan', [KeteranganController::class, 'loadTableKeterangan'])->name('load-table-keterangan')->middleware('auth.web_or_admin');
Route::post('store-keterangan', [KeteranganController::class, 'store'])->middleware('auth.web_or_admin');
Route::post('destroy-keterangan', [KeteranganController::class, 'destroy'])->middleware('auth.web_or_admin');

// user wifi
Route::get('/user-wifi', [UserWifiController::class, 'index'])->name('user-wifi')->middleware('auth.web_or_admin');
Route::post('load-table-user-wifi', [UserWifiController::class, 'loadTableUserWifi'])->name('load-table-user-wifi')->middleware('auth.web_or_admin');
Route::post('load-kode-pegawai', [UserWifiController::class, 'loadKodePegawai'])->name('load-kode-pegawai')->middleware('auth.web_or_admin');
Route::post('form-save-user-wifi', [UserWifiController::class, 'saveUserWifi'])->name('form-save-user-wifi')->middleware('auth.web_or_admin');
Route::post('destroy-user-wifi', [UserWifiController::class, 'destroyUserWifi'])->name('destroy-user-wifi')->middleware('auth.web_or_admin');
Route::post('status-user-wifi', [UserWifiController::class, 'statusUserWifi'])->name('status-user-wifi')->middleware('auth.web_or_admin');
Route::get('/tampil-surat/{id}', [UserWifiController::class, 'tampilSurat'])->name('tampil-surat');
Route::get('/tampil-surat-dev/{id}', [UserWifiController::class, 'tampilSuratDev'])->name('tampil-surat-dev');
Route::post('kirim-surat-pengajuan-user-wifi', [UserWifiController::class, 'kirimSuratPengajuanUserWifi'])->name('kirim-surat-pengajuan-user-wifi')->middleware('auth.web_or_admin');
Route::post('kirim-user-pengajuan-user-wifi', [UserWifiController::class, 'kirimUserPengajuanUserWifi'])->name('kirim-user-pengajuan-user-wifi')->middleware('auth.web_or_admin');

// user-asset
Route::get('/user-asset', [UserAssetController::class, 'index'])->name('user-asset')->middleware('auth.web_or_admin');
Route::post('load-table-user-asset', [UserAssetController::class, 'loadTableUserAsset'])->name('load-table-user-asset')->middleware('auth.web_or_admin');
Route::post('form-save-user-asset', [UserAssetController::class, 'saveUserAsset'])->name('form-save-user-asset')->middleware('auth.web_or_admin');
Route::post('destroy-user-asset', [UserAssetController::class, 'destroyUserAsset'])->name('destroy-user-asset')->middleware('auth.web_or_admin');
Route::post('status-user-asset', [UserAssetController::class, 'statusUserAsset'])->name('status-user-asset')->middleware('auth.web_or_admin');

// report-data-user
Route::get('/report-data-user', [ReportDataUserController::class, 'index'])->name('report-data-user')->middleware('auth.web_or_admin');
Route::post('load-table-report-data-user', [ReportDataUserController::class, 'loadTableReportDataUser'])->name('load-table-report-data-user')->middleware('auth.web_or_admin');

// report-jumlah-user
Route::get('/report-jumlah-user', [ReportJumlahUserController::class, 'index'])->name('report-jumlah-user')->middleware('auth.web_or_admin');
Route::post('load-tampil-data-report-jumlah-user', [ReportJumlahUserController::class, 'loadTampilDataReportJumlahUser'])->name('load-tampil-data-report-jumlah-user')->middleware('auth.web_or_admin');
Route::post('load-table-detail-data-user', [ReportJumlahUserController::class, 'loadTableDetailDataUser'])->name('load-table-detail-data-user')->middleware('auth.web_or_admin');
Route::post('load-table-detail-data-asset', [ReportJumlahUserController::class, 'loadTableDetailDataAsset'])->name('load-table-detail-data-asset')->middleware('auth.web_or_admin');

// select
Route::post('select-tahun-pelajaran', [SelectController::class, 'getTahunPelajaran'])->name('select-tahun-pelajaran')->middleware('auth.web_or_admin');
Route::post('select-level', [SelectController::class, 'getLevel'])->name('select-level')->middleware('auth.web_or_admin');
Route::post('select-bidang', [SelectController::class, 'getBidang'])->name('select-bidang')->middleware('auth.web_or_admin');
Route::post('select-jenis-kegiatan', [SelectController::class, 'getJenisKegiatan'])->name('select-jenis-kegiatan')->middleware('auth.web_or_admin');
Route::post('select-status-capaian', [SelectController::class, 'getStatusCapaian'])->name('select-status-capaian')->middleware('auth.web_or_admin');
Route::post('select-program-kerja-by-user', [SelectController::class, 'getProgramKerjaByUser'])->name('select-program-kerja-by-user')->middleware('auth.web_or_admin');
Route::post('select-data-pegawai-by-so', [SelectController::class, 'dataPegawaiBySo'])->name('select-data-pegawai-by-so')->middleware('auth.web_or_admin');
Route::post('select-user-level', [SelectController::class, 'getUserLevel'])->name('select-user-level')->middleware('auth.web_or_admin');

Route::post('/select-nama-pegawai', [SelectController::class, 'namaPegawai'])->name('nama-pegawai')->middleware('auth.web_or_admin');
Route::post('/select-nama-asset', [SelectController::class, 'namaAsset'])->name('select-nama-asset')->middleware('auth.web_or_admin');


// login
Route::get('/', [LoginController::class, 'index']);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/reload-captcha', [LoginController::class, 'reloadCaptcha']);

// regis
// Route::get('/registrasi-awal', [RegistrasiAwalController::class, 'index'])->name('registrasi-awal')->middleware('auth.web_or_admin');
// Route::post('admin/load-tabel-registrasi-awal', [RegistrasiAwalController::class, 'loadTabelRegistrasiAwal'])->name('load-tabel-registrasi-awal')->middleware('admin');

// level
Route::get('/admin/level', [LevelController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');;
Route::post('admin/load-tabel-level', [LevelController::class, 'loadTabelLevel'])->name('load-tabel-level');
Route::post('admin/store-level', [LevelController::class, 'store']);
Route::post('admin/destroy-level', [LevelController::class, 'destroy']);

// user
Route::get('/admin/user', [UserController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');;
Route::post('admin/load-tabel-user', [UserController::class, 'loadTabelUser'])->name('load-tabel-user');
Route::post('admin/hapus-akses', [UserController::class, 'hapusAkses']);
Route::post('admin/simpan-level-user', [UserController::class, 'simpanLevelUser']);
Route::post('admin/simpan-bidang-user', [UserController::class, 'simpanBidangUser']);

// user-non-civitas
Route::get('/user-non-civitas', [UserNonCivitasController::class, 'index'])->middleware('auth.web_or_admin')->middleware('cekmenuakses');;
Route::post('load-tabel-user-non-civitas', [UserNonCivitasController::class, 'loadTabelUserNonCivitas'])->name('load-tabel-user-non-civitas');
Route::post('store-user-non-civitas', [UserNonCivitasController::class, 'store']);
Route::post('destroy-user-non-civitas', [UserNonCivitasController::class, 'destroy']);
// tampil-mapping-bawahan
Route::post('tampil-mapping-bawahan', [UserNonCivitasController::class, 'tampilMappingBawahan']);
Route::post('tabel-mapping-bawahan', [UserNonCivitasController::class, 'tabelMappingBawahan']);
Route::post('save-data-bawahan', [UserNonCivitasController::class, 'saveDataBawahan']);
Route::post('hapus-data-bawahan', [UserNonCivitasController::class, 'hapusDataBawahan']);



// menu header
Route::get('/admin/menu', [MenuController::class, 'index'])->middleware('auth.web_or_admin');
Route::post('admin/load-tabel-menu-header', [MenuController::class, 'loadTabelMenuHeader'])->name('load-tabel-menu-header');
Route::post('admin/store-menu-header', [MenuController::class, 'storeMenuHeader']);
Route::post('admin/detail-menu-header', [MenuController::class, 'detailMenuHeader']);
// menu parent
Route::post('admin/load-tabel-menu-parent', [MenuController::class, 'loadTabelMenuParent'])->name('load-tabel-menu-parent');
Route::post('admin/store-menu-parent', [MenuController::class, 'storeMenuParent']);
Route::post('admin/destroy-menu-parent', [MenuController::class, 'destroyParent']);

// akses
Route::get('/admin/akses', [AksesController::class, 'index'])->middleware('auth.web_or_admin');
Route::post('admin/tampil-level-akses', [AksesController::class, 'tampilLevelAkses'])->name('tampil-level-akses');
Route::post('admin/simpan-akses-menu', [AksesController::class, 'simpanAksesMenu']);

