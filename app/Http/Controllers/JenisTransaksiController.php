<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTransaksi;
use Yajra\DataTables\DataTables;

class JenisTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Jenis Transaksi | Gian Cellular',
            'badge' => 'Jenis Transaksi',
            'jenis' => JenisTransaksi::all()
        ];

        return view('jenis.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_transaksi' => 'required',
            'laba' => 'required',
        ]);

        JenisTransaksi::updateOrInsert($data);

        echo json_encode($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisTransaksi $jenisTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisTransaksi $jenisTransaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisTransaksi $jenisTransaksi)
    {
        
        $data = $request->validate([
            'jenis_transaksi' => 'required',
            'laba' => 'required',
        ]);

        echo json_encode($data);
        JenisTransaksi::where('id', $request->id)->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisTransaksi $jenisTransaksi)
    {
        JenisTransaksi::destroy($jenisTransaksi->id);
        return redirect('/jenis-transaksi')->with('success', 'Data Berhasil Dihapus');
    }
}
