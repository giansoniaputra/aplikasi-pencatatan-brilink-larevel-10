<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Models\Saldo;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class SaldoController extends Controller
{
    public function index()
    {
        $modal = Modal::first();
        $hutang = Transaksi::select('hutang')->get()->sum->hutang;
        $saldo = Saldo::first();
        $laba = ($hutang + $saldo->saldo_d + $saldo->saldo_k) - $modal->modal;
        $data = [
            'title' => 'Informasi Saldo | Gian Cellular',
            'badge' => 'Informasi Saldo',
            'saldo' => Saldo::first(),
            'hutang' => $hutang,
            'laba' => $laba,
            'total' => $hutang + $saldo->saldo_d + $saldo->saldo_k,
        ];

        return view('saldo.index', $data);
    }
}
