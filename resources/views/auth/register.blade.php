@extends('layouts.main')
@section('container')
<div class="row my-3">
    <div class="col">
        <button type="button" class="btn btn-primary btn-icon-split" id="btn-add-data" data-toggle="modal" data-target="#modal-form">
            <span class="icon text-white-50">
                <i class="fas fa-flag"></i>
            </span>
            <span class="text">Tambah User</span>
        </button>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
