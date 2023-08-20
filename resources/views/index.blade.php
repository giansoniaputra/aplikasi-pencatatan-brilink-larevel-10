@extends('layouts.main')
@section('container')
@if (auth()->user()->role == "ADMIN")
<div class="row">
    @foreach ($members as $row)
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <h6 class="font-weight-bold">Jumlah Fee {{ $row->nama_user}}</h6>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <h3 class="font-weight-bold">@currency($row->jumlah_fee)</h3>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <a href="/clearFee/{{ $row->id }}" class="btn btn-primary btn-sm" onclick="return confirm('Yakin Ingin Mereset Fee?')">Clear</a>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@elseif (auth()->user()->role == "MEMBER")
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <h6 class="font-weight-bold">Jumlah Fee {{ ucwords(strtolower(auth()->user()->nama_user)) }}</h6>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <h3 class="font-weight-bold">@currency($member->jumlah_fee)</h3>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
