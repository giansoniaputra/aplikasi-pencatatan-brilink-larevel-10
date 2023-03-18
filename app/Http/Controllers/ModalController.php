<?php

namespace App\Http\Controllers;

use App\Models\Modal;
use App\Models\Model;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Informasi Modal | Gian Cellular',
            'badge' => 'Informasi Modal',
            'modal' => Modal::first()
        ];

        return view('modal.index', $data);
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modal $modal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modal $modal)
    {
        Modal::where('id', $modal->id)
                ->update(['modal' => $request->modal]);
        return redirect('modal')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modal $modal)
    {
        //
    }
}
