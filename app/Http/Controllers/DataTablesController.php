<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisTransaksi;
use Yajra\DataTables\DataTables;

class DataTablesController extends Controller
{
    public function dataTables(Request $request)
    {
        if ($request->ajax()) {
            $query = JenisTransaksi::select('id','jenis_transaksi', 'laba')->orderBy('jenis_transaksi', 'asc');
            $data = $query->get();
            
            foreach( $data as $row ) {
                $row->labaTrans = "Rp " . number_format($row->laba,0,',','.');
                $row->jenis = strtoupper($row->jenis_transaksi);
            };
    
            return Datatables::of($data)->addColumn('action', function($row){
                $actionBtn = 
                '<button class="btn btn-warning btn-sm edit-button" data-id="'.$row->id.'"><i class="fas fa-exclamation-triangle"></i></button> 
                <form action="/jenis-transaksi/'.$row->id.'" method="post" class="d-inline">
                    <input type="hidden" name="_token" value="'.csrf_token().'" />
                    <input type="hidden" name="_method" value="DELETE">
                    <button class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus data?\')"><i class="fas fa-trash"></i></button>
                </form>';
                return $actionBtn;
            })->make(true);
        }
    }

    public function updateModal(Request $request, JenisTransaksi $jenis)
    {
        $data = $jenis->select('*')
        ->where('id', $request->id)->first();

        echo json_encode($data);
    }
}
