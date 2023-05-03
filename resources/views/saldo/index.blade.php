@extends('layouts.main')

@section('container')
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Saldo Debit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($saldo->saldo_d)</div>
                        <input type="hidden" value="{{ $saldo->saldo_d }}" id="debit">
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Saldo Kredit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($saldo->saldo_k)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Laba</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($laba)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Hutang</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($hutang)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($total)</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4 mt-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Uang di Debit</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="hasil">Rp. 0</div>
                            <div id="balance"></div>
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
<br>
<h2 class="h2">Hitung Jumlah Uang</h2>
<div class="row">
    <div class="col-md-2 mb-4">
        <label for="name" class="form-label">Rp. 100.000</label>
        <input type="number" class="form-control" id="seratus">
        <br>
        <label for="name" class="form-label">Rp. 75.000</label>
        <input type="number" class="form-control" id="tujuh-lima">
        <br>
        <label for="name" class="form-label">Rp. 50.000</label>
        <input type="number" class="form-control" id="lima-puluh">
        <br>
        <label for="name" class="form-label">Rp. 20.000</label>
        <input type="number" class="form-control " id="dua-puluh">
    </div>
    <div class="col-md-2 mb-4">
        <label for="name" class="form-label">Rp. 10.000</label>
        <input type="number" class="form-control" id="sepuluh">
        <br>
        <label for="name" class="form-label">Rp. 5.000</label>
        <input type="number" class="form-control" id="lima">
        <br>
        <label for="name" class="form-label">Rp. 2.000</label>
        <input type="number" class="form-control" id="dua">
        <br>
        <label for="name" class="form-label">Rp. 1.000</label>
        <input type="number" class="form-control" id="satu">
    </div>
    <div class="col-md-12">
        <button type="button" id="hitung" class="btn btn-primary">Hitung</button>
    </div>
</div>

<script>
    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $(document).ready(function () {
        const seratus = parseInt($("#seratus").val()) * 100000;
        const tujuh_lima = parseInt($("#tujuh-lima").val()) * 75000;
        const lima_puluh = parseInt($("#lima-puluh").val()) * 50000;
        const dua_puluh = parseInt($("#dua-puluh").val()) * 20000;
        const sepuluh = parseInt($("#sepuluh").val()) * 10000;
        const lima = parseInt($("#lima").val()) * 5000;
        const dua = parseInt($("#dua").val()) * 2000;
        const satu = parseInt($("#satu").val()) * 1000;
        const debit = parseInt($("#debit").val());


        $("#hitung").on('click', function () {
            const hasil = parseInt($("#seratus").val()) * 100000 + parseInt($("#tujuh-lima").val()) *
                75000 + parseInt($("#lima-puluh").val()) * 50000 + parseInt($("#dua-puluh").val()) *
                20000 + parseInt($("#sepuluh").val()) * 10000 + parseInt($("#lima").val()) * 5000 +
                parseInt($("#dua").val()) * 2000 + parseInt($("#satu").val()) * 1000;
            let total = hasil.toString()
            if (hasil < debit) {
                let kurang = (debit - hasil).toString()
                $(".hasil").html(formatRupiah(total, 'Rp. '))
                $("#balance").html('<small class="text-danger">Kurang '+ formatRupiah(kurang, 'Rp. ') + '</small>' );
            } else if (hasil > debit) {
                let lebih = (hasil - debit).toString()
                $(".hasil").html(formatRupiah(total, 'Rp. '))
                $("#balance").html('<small class="text-success">Lebih '+ formatRupiah(lebih, 'Rp. ') + '</small>' );
            } else if (hasil == debit) {
                $(".hasil").html('BALANCE')
            }
        })
    })

</script>
@endsection
