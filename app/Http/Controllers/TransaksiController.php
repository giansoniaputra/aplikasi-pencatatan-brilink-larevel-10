<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Models\Saldo;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\JenisTransaksi;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Transaksi | Gian Cellular',
            'badge' => 'Transaksi',
            'modal' => Modal::first(),
            'jeniss' => DB::table('jenis_transaksi')->orderBy('jenis_transaksi', 'asc')->get(),
            'transaksis' => Transaksi::all(),
            'tanggal_awal' => Carbon::now()->startOfMonth()->toDateString(),
            'tanggal_akhir' => Carbon::now()->endOfMonth()->toDateString(),
        ];

        return view('transaksi.index', $data);
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
        $rules = $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'jenis2' => 'required',
            'tanggal' => 'required',
            'status' => 'required',
            'debit' => 'required',
            'kredit' => 'required',
            'laba' => 'required',
        ]);

        $saldo = Saldo::first();

        $nama = $request->nama;
        $jenis = $request->jenis;
        $jenis2 = $request->jenis2;
        $tanggal = $request->tanggal;
        $status = $request->status;
        $debit = preg_replace('/[,]/', '', $request->debit);
        $kredit = preg_replace('/[,]/', '', $request->kredit);
        $laba = preg_replace('/[,]/', '', $request->laba);

        if ($status == 'LUNAS(DEBIT)') {
            $saldo_d = $saldo->saldo_d + $debit - $kredit;
            $saldo_k = $saldo->saldo_k - $debit + $kredit;
            $hutang = '0';
        } else if ($status == 'LUNAS(KREDIT)') {
            $saldo_d = $saldo->saldo_d;
            $saldo_k = $saldo->saldo_k - $debit + $kredit;
            $hutang = '0';
        } else if ($status == 'BELUM LUNAS') {
            $saldo_d = $saldo->saldo_d;
            $saldo_k = $saldo->saldo_k - $debit + $kredit;
            $hutang = $debit + $kredit;
        } else if ($status == 'PINJAMAN') {
            $saldo_d = $saldo->saldo_d - $debit;
            $saldo_k = $saldo->saldo_k;
            $hutang = $debit;
        } else if ($status == 'LABA') {
            $saldo_d = $saldo->saldo_d - $debit;
            $saldo_k = $saldo->saldo_k;
            $hutang = '0';
        } else if ($status == 'PENAMBAHAN') {
            $saldo_d = $saldo->saldo_d + $debit;
            $saldo_k = $saldo->saldo_k + $kredit;
            $hutang = '0';
        } else if ($status == 'PENGURANGAN') {
            $saldo_d = $saldo->saldo_d - $debit;
            $saldo_k = $saldo->saldo_k - $kredit;
            $hutang = '0';
        }

        $data = Transaksi::create([
            'nama' => $nama,
            'jenis' => $jenis,
            'jenis2' => $jenis2,
            'tanggal' => $tanggal,
            'status' => $status,
            'debit' => $debit,
            'kredit' => $kredit,
            'laba' => $laba,
            'hutang' => $hutang,
        ]);

        $data->save();

        Saldo::where('id', $saldo->id)->update([
            'saldo_d' => $saldo_d,
            'saldo_k' => $saldo_k,
        ]);

        echo json_encode($data);
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
        $rules = $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'jenis2' => 'required',
            'tanggal' => 'required',
            'status' => 'required',
            'debit' => 'required',
            'kredit' => 'required',
            'laba' => 'required',
        ]);

        $last_transaksi = DB::table('transaksi')->where('id', $request->id)->first();

        $saldo = Saldo::first();

        //data baru
        $nama = $request->nama;
        $jenis = $request->jenis;
        $jenis2 = $request->jenis2;
        $tanggal = $request->tanggal;
        $s_baru = $request->status;
        $d_baru = preg_replace('/[,]/', '', $request->debit);
        $k_baru = preg_replace('/[,]/', '', $request->kredit);
        $laba = preg_replace('/[,]/', '', $request->laba);

        //data lama
        $status = $last_transaksi->status;
        $debit = $last_transaksi->debit;
        $kredit = $last_transaksi->kredit;
        $s_debit = $saldo->saldo_d;
        $s_kredit = $saldo->saldo_k;

        if ($s_baru == 'LUNAS(DEBIT)') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d + $d_baru - $k_baru;
            $saldo_kb = $saldo_k - $d_baru + $k_baru;
            $hutang = '0';
        } elseif ($s_baru == 'LUNAS(KREDIT)') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d;
            $saldo_kb = $saldo_k - $d_baru + $k_baru;
            $hutang = '0';
        } elseif ($s_baru == 'BELUM LUNAS') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d;
            $saldo_kb = $saldo_k - $d_baru + $k_baru;
            $hutang = $d_baru + $k_baru;
        } elseif ($s_baru == 'PINJAMAN') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d - $d_baru;
            $saldo_kb = $saldo_k;
            $hutang = $d_baru;
        } elseif ($s_baru == 'LABA') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d - $d_baru;
            $saldo_kb = $saldo_k;
            $hutang = '0';
        } elseif ($s_baru == 'PENAMBAHAN') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d + $d_baru;
            $saldo_kb = $saldo_k + $k_baru;
            $hutang = '0';
        } elseif ($s_baru == 'PENGURANGAN') {
            if ($status == 'LUNAS(DEBIT)') {
                $saldo_d = $s_debit - $debit + $kredit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'LUNAS(KREDIT)') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'BELUM LUNAS') {
                $saldo_d = $s_debit;
                $saldo_k = $s_kredit + $debit - $kredit;
                // $hutang = $debit+$kredit;
            } elseif ($status == 'PINJAMAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = $debit;
            } elseif ($status == 'LABA') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit;
                // $hutang = '0';
            } elseif ($status == 'PENAMBAHAN') {
                $saldo_d = $s_debit - $debit;
                $saldo_k = $s_kredit - $kredit;
                // $hutang = '0';
            } elseif ($status == 'PENGURANGAN') {
                $saldo_d = $s_debit + $debit;
                $saldo_k = $s_kredit + $kredit;
                // $hutang = '0';
            }
            $saldo_db = $saldo_d - $d_baru;
            $saldo_kb = $saldo_k - $k_baru;
            $hutang = '0';
        }

        $data = [
            'nama' => $nama,
            'jenis' => $jenis,
            'jenis2' => $jenis2,
            'tanggal' => $tanggal,
            'status' => $s_baru,
            'debit' => $d_baru,
            'kredit' => $k_baru,
            'laba' => $laba,
            'hutang' => $hutang,
        ];


        $data2 = [
            'saldo_d' => $saldo_db,
            'saldo_k' => $saldo_kb,
        ];

        Transaksi::where('id', $request->id)->update($data);
        Saldo::where('id', $saldo->id)->update($data2);


        echo json_encode($data2);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Transaksi $transaksi)
    {
        //Memilih debit dan kredit dari id yang di pilih
        $last_transaksi = Transaksi::where('id', $request->id)->first();
        $debit = $last_transaksi->debit;
        $kredit = $last_transaksi->kredit;
        $status = $last_transaksi->status;

        //mengambil saldo
        $saldo = Saldo::first();
        $saldo_d = $saldo->saldo_d;
        $saldo_k = $saldo->saldo_k;

        //mengembalikan saldo ke semula
        if ($status == 'LUNAS(DEBIT)') {
            $sd_semula = $saldo_d - $debit + $kredit;
            $sk_semula = $saldo_k + $debit - $kredit;
            // $hutang = '0';
        } elseif ($status == 'LUNAS(KREDIT)') {
            $sd_semula = $saldo_d;
            $sk_semula = $saldo_k + $debit - $kredit;
            // $hutang = '0';
        } elseif ($status == 'BELUM LUNAS') {
            $sd_semula = $saldo_d;
            $sk_semula = $saldo_k + $debit - $kredit;
            // $hutang = $debit+$kredit;
        } elseif ($status == 'PINJAMAN') {
            $sd_semula = $saldo_d + $debit;
            $sk_semula = $saldo_k;
            // $hutang = $debit;
        } elseif ($status == 'LABA') {
            $sd_semula = $saldo_d + $debit;
            $sk_semula = $saldo_k;
            // $hutang = '0';
        } elseif ($status == 'PENAMBAHAN') {
            $sd_semula = $saldo_d - $debit;
            $sk_semula = $saldo_k - $kredit;
            // $hutang = '0';
        } elseif ($status == 'PENGURANGAN') {
            $sd_semula = $saldo_d + $debit;
            $sk_semula = $saldo_k + $kredit;
            // $hutang = '0';
        }

        $data = [
            'saldo_d' => $sd_semula,
            'saldo_k' => $sk_semula,
        ];
        Saldo::where('id', $saldo->id)->update($data);

        $data2 =  Transaksi::where('id', $request->id)->delete();
        echo json_encode($data2);
    }


    //Memanggil Datatables
    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaksi::where("tanggal", ">=", $request->tanggal_awal)
                ->where("tanggal", "<=", $request->tanggal_akhir)
                ->get();

            foreach ($data as $row) {
                $row->nama_t = strtoupper($row->nama);
                $row->debit_t = "Rp " . number_format($row->debit, 0, ',', '.');
                $row->kredit_t = "Rp " . number_format($row->kredit, 0, ',', '.');
                $row->jenis_t = strtoupper($row->jenis);
                $row->tanggal_t = date('d F Y', strtotime($row->tanggal));
            };

            return Datatables::of($data)->addColumn('action', function ($row) {
                $actionBtn =
                    '<button class="btn btn-warning btn-sm edit-button" data-id="' . $row->id . '"><i class="fas fa-exclamation-triangle"></i></button> 
                <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)" class="d-inline form-delete">
                    <input type="hidden" name="_token" value="' . csrf_token() . '" />
                    <input type="hidden" name="id" value="' . $row->id . '" />
                    <button type="button" class="btn btn-danger btn-sm delete-button" data-token="' . csrf_token() . '" data-id="' . $row->id . '" id="deleteBtn"><i class="fas fa-trash"></i></button>
                </form>';
                return $actionBtn;
            })->make(true);
        }
    }

    //MEmanggil data untuk edit modal

    public function updateModal(Request $request, Transaksi $transaksi)
    {
        $data = $transaksi->select('*')
            ->where('id', $request->id)->first();

        echo json_encode($data);
    }


    public function autofill_jenis(Request $request)
    {
        $jenis = DB::table('jenis_transaksi')->where('jenis_transaksi', $request->jenis)->first();

        echo json_encode($jenis);
    }
    public function autofill_status(Request $request)
    {
        if ($request->status == 'LUNAS(DEBIT)' || $request->status == 'BELUM LUNAS') {
            $jenis = DB::table('jenis_transaksi')->where('jenis_transaksi', $request->jenis)->first();
        } else {
            $jenis = [
                'laba' => 0
            ];
        }
        echo json_encode($jenis);
    }
}
