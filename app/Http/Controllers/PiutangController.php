<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Piutang | Gian Cellular',
            'badge' => 'Informasi Piutang',
        ];

        return view('piutang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }

    //Memanggil Datatables
    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('transaksi')->where('status', 'BELUM LUNAS')->orWhere('status', 'PINJAMAN');
            $data = $query->get();
            
            foreach( $data as $row ) {
                $row->nama_t = strtoupper($row->nama);
                $row->debit_t = "Rp " . number_format($row->debit,0,',','.');
                $row->kredit_t = "Rp " . number_format($row->kredit,0,',','.');
                $row->jenis_t = strtoupper($row->jenis);
                $row->tanggal_t = date('d F Y', strtotime($row->tanggal));
            };
    
            return Datatables::of($data)->addColumn('action', function($row){
                $actionBtn = 
                '
                 <button class="btn btn-success btn-sm debit-button" data-id="'.$row->id.'" data-token="'.csrf_token().'"><i class="fas fa-money-bill-wave-alt"></i></button>
                 <button class="btn btn-success btn-sm kredit-button" data-id="'.$row->id.'" data-kredit="'.($row->debit + $row->kredit).'" data-nominal="Rp. '. number_format($row->debit + $row->kredit,0,',','.').'"><i class="far fa-credit-card"></i></button>
                ';
                return $actionBtn;
            })->make(true);
        }
    }

    //Lunas Debit
    public function lunas_debit(Request $request)
    {
        $last_transaksi = Transaksi::where('id', $request->id)->first();
        $debit = $last_transaksi->debit;
        $kredit = $last_transaksi->kredit;

        $saldo = Saldo::first();
        $s_debit = $saldo->saldo_d;
        $s_kredit = $saldo->saldo_k;

        $saldo_d = $s_debit+$debit-$kredit;
		$saldo_k = $s_kredit;
		$status_b = 'LUNAS(DEBIT)';
		$hutang_b = '0';

        Transaksi::where('id',$last_transaksi->id)->update([
            'status' => $status_b,
            'hutang' => $hutang_b,
        ]);

        Saldo::where('id',$saldo->id)->update([
            'saldo_d' => $saldo_d,
            'saldo_k' => $saldo_k,
        ]);
        echo json_encode($saldo);
    }

    public function lunas_kredit(Request $request)
    {
        $rules = $request->validate([
            'kredit' => 'required'
        ]);

        $id = $request->id;
        $new_kredit = $request->kredit;

        // $last_transaksi = Transaksi::where('id', $id)->first();
        $saldo = Saldo::first();

        $s_kredit = $saldo->saldo_k + $new_kredit;

        $data = [
            'status' => 'LUNAS(KREDIT)',
            'kredit' => $new_kredit,
            'hutang' => 0,
        ];

        $data2 = [
            'saldo_k' => $s_kredit
        ];

        Transaksi::where('id', $id)->update($data);
        Saldo::where('id', $saldo->id)->update($data2);

        echo json_encode($data);
    }
}
