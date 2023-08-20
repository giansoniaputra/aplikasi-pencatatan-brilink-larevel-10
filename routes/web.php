<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DataTablesController;
use App\Http\Controllers\JenisTransaksiController;
use App\Models\FeeMember;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index', [
        'title' => 'Gian Cellular',
        'badge' => 'Dashboard',
        'members' => FeeMember::all(),
        'member' => FeeMember::where('member', auth()->user()->id)->first(),
    ]);
})->middleware('auth');

Route::get('/home', function () {
    return view('index', [
        'title' => 'Gian Cellular',
        'badge' => 'Dashboard',
        'members' => FeeMember::all(),
        'member' => FeeMember::where('member', auth()->user()->id)->first(),
    ]);
})->middleware('auth');

Route::get('/auth', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/auth', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

//MODAL-------------------------------------------------------------------------
// Route Halaman Modal
Route::get('/modal', [ModalController::class, 'index'])->middleware('auth');
// Route Action Modal
Route::resource('/modal', ModalController::class)->middleware('auth');

//TRANSAKSI--------------------------------------------------------------------
Route::get('/transaksi', [TransaksiController::class, 'index'])->middleware('auth');
Route::get('/transaksi/edit', [TransaksiController::class, 'updateModal'])->name('edit-transaksi')->middleware('auth');
Route::post('/transaksi/update', [TransaksiController::class, 'update'])->name('update-transaksi')->middleware('auth');
Route::post('/transaksi/delete', [TransaksiController::class, 'destroy'])->name('hapus-transaksi')->middleware('auth');
Route::get('/transaksi/autofill', [TransaksiController::class, 'autofill_jenis'])->name('change-jenis')->middleware('auth');
Route::get('/transaksi/autofill_2', [TransaksiController::class, 'autofill_status'])->name('change-status')->middleware('auth');
Route::resource('/transaksi', TransaksiController::class)->middleware('auth');

//JENIS TRANSAKSI--------------------------------------------------------------------
//Route Halaman Jenis Transaksi
Route::get('/jenis-transaksi', [JenisTransaksiController::class, 'index'])->middleware('auth');
//Route Create
Route::resource('/jenis-transaksi', JenisTransaksiController::class)->middleware('auth');
Route::post('/jenis-transaksi', [JenisTransaksiController::class, 'store'])->name('create_jenis')->middleware('auth');
Route::get('/updateModal', [DataTablesController::class, 'updateModal'])->name('edit_jenis')->middleware('auth');
Route::post('/jenis-transaksi/update', [JenisTransaksiController::class, 'update'])->name('update_jenis')->middleware('auth');
//Delete
Route::post('/jenis-transaksi/destroy', [JenisTransaksiController::class, 'destroy'])->middleware('auth');

//SALDO----------------------------------------------------------------------------------------------------
Route::get('/saldo', [SaldoController::class, 'index'])->middleware('auth');

//PIUTANG--------------------------------------------------------------------------------------------------
Route::get('/piutang', [PiutangController::class, 'index'])->middleware('auth');
Route::post('/piutang/lunas-debit', [PiutangController::class, 'lunas_debit'])->name('lunas-debit')->middleware('auth');
Route::post('/piutang/lunas-kredit', [PiutangController::class, 'lunas_kredit'])->name('lunas-kredit')->middleware('auth');

//Clear Fee
Route::get('/clearFee/{id_member}', [TransaksiController::class, 'clear_fee'])->middleware('auth');


// DATATABLES---------------------------------------------------------------------------------
Route::get('/dataTables', [DataTablesController::class, 'dataTables'])->name('jenis.dataTables');
Route::get('/dataTablesTransaksi', [TransaksiController::class, 'dataTables'])->name('transaksi.dataTables');
Route::get('/dataTablesPiutang', [PiutangController::class, 'dataTables'])->name('piutang.dataTables');
