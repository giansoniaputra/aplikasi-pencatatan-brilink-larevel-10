@extends('layouts.main')
@section('container')
<div class="row my-3">
    <div class="col">
        <button type="button" class="btn btn-primary btn-icon-split" id="tombol-input" data-toggle="modal" data-target="#modal-form">
            <span class="icon text-white-50">
                <i class="fas fa-flag"></i>
            </span>
            <span class="text">Input Transaksi</span>
        </button>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row">
            <div class="col-sm-3">
                <input type="date" id="tanggal-awal" value="{{ $tanggal_awal }}" class="form-control">
            </div>
            <div class="col-sm-3">
                <input type="date" id="tanggal-akhir" value="{{ $tanggal_akhir }}" class="form-control">
            </div>
            <div class="col-sm-6">
                <button class="btn btn-secondary" id="search-tanggal">Cari</button>
                <button class="btn btn-secondary" id="search-today">Today</button>
                <button class="btn btn-secondary" id="search-1">-1</button>
                <button class="btn btn-secondary" id="search-2">-2</button>
                <button class="btn btn-secondary" id="search-3">-3</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Jenis Transaksi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Input Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)">
                    <div class="id-last"></div>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" placeholder="Enter name" name="nama">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-nama"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Jenis Transaksi</label>
                        <select class="form-control" data-toggle="select2" data-width="100%" name="jenis" id="jenis" onchange="autofill()">
                            <option value="">Pilih Jenis Transaksi</option>
                            @foreach( $jeniss as $row )
                            <option value="{{ $row->jenis_transaksi }}">
                                {{ $row->jenis_transaksi }}
                            </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-jenis"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jenis2" class="form-label">BRI/NON-BRI</label>
                        <select class="form-control" data-toggle="select2" data-width="100%" name="jenis2" id="jenis2">
                            <option value="bri" selected>BRILINK</option>
                            <option value="non">NON-BRILINK</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-jenis2"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Tanggal Transaksi</label>
                        <input class="form-control" type="date" name="tanggal" id="tanggal">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-tanggal"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Status</label>
                        <select class="form-control" data-toggle="select2" data-width="100%" name="status" id="status" onchange="autofill_2()">
                            <option value="">Pilih Status Transaksi</option>
                            <option value="LUNAS(DEBIT)">LUNAS(DEBIT)</option>
                            <option value="LUNAS(KREDIT)">LUNAS(KREDIT)</option>
                            <option value="BELUM LUNAS">BELUM LUNAS</option>
                            <option value="PINJAMAN">PINJAMAN</option>
                            <option value="LABA">LABA</option>
                            <option value="PENAMBAHAN">PENAMBAHAN</option>
                            <option value="PENGURANGAN">PENGURANGAN</option>
                        </select>
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-status"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Debit</label>
                        <input class="form-control money" id="debit" type="text" name="debit" value="0">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-debit"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Kredit</label>
                        <input class="form-control money" id="kredit" type="text" name="kredit" value="0">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-kredit"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Laba</label>
                        <input class="form-control money" id="laba" type="text" name="laba" @if (auth()->user()->role == "MEMBER") readonly @endif>
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-laba"></span>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="batal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveAction" id="saveBtn">Create</button>
                <button type="button" class="btn btn-primary editAction" id="editBtn">Edit</button>
            </div>
            </form>
        </div>
    </div>
</div>
{{-- Simple Money Format --}}
<script src="/js/simple.money.format.js"></script>
<script src="/js/simple.money.format.init.js"></script>
{{-- !Simple Money Format --}}
<script>
    let angka = document.querySelector('#debit')
    angka.addEventListener('keyup', function() {
        let trim = angka.value.trim()
        if (trim.charAt(0) == 0 && trim.charAt(1) == true) {
            angka.value = trim.charAt(1)
        }
    })
    let angka2 = document.querySelector('#kredit')
    angka2.addEventListener('keyup', function() {
        let trim = angka2.value.trim()
        if (trim.charAt(0) == 0 && trim.charAt(1) == true) {
            angka2.value = trim.charAt(1)
        }
    })
    $(document).ready(function() {
        $("#debit").on('keyup', function() {
            $("input.money").simpleMoneyFormat({
                currencySymbol: "Rp"
                , decimalPlaces: 0
                , thousandsSeparator: "."
            , });
        })
        $("#kredit").on('keyup', function() {
            $("input.money").simpleMoneyFormat({
                currencySymbol: "Rp"
                , decimalPlaces: 0
                , thousandsSeparator: "."
            , });
        })
        $("#laba").on('keyup', function() {
            $("input.money").simpleMoneyFormat({
                currencySymbol: "Rp"
                , decimalPlaces: 0
                , thousandsSeparator: "."
            , });
        })
    })

</script>
<script>
    $(document).ready(function() {
        //Inialisasi Datatables
        let table = $('#dataTable').DataTable({
            processing: true
            , responsive: true
            , searching: true
            , bLengthChange: true
            , info: false
            , ordering: true
            , serverSide: true
            , ajax: {
                url: "{{ route('transaksi.dataTables') }}"
                , type: "GET"
                , data: function(d) {
                    d.tanggal_awal = $("#tanggal-awal").val();
                    d.tanggal_akhir = $("#tanggal-akhir").val();
                }
            , }
            , "columns": [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                , }
                , {
                    "data": 'nama_t'
                }
                , {
                    "data": 'jenis_t'
                }
                , {
                    "data": 'tanggal_t'
                }
                , {
                    "data": 'status'
                }
                , {
                    "data": 'debit_t'
                }
                , {
                    "data": 'kredit_t'
                }
                , {
                    "data": 'action'
                    , "orderable": true
                    , "searchable": true
                }
            , ]
            , "order": [
                [0, 'desc']
            ]

        });
        let tanggal_awal = $("#tanggal-awal");
        let tanggal_akhir = $("#tanggal-akhir");
        $("#search-tanggal").on("click", function() {
            table.ajax.reload();
        })
        $("#search-today").on("click", function() {
            let selectedDate = new Date();
            selectedDate.setDate(selectedDate.getDate());
            let formattedDate = selectedDate.toISOString().slice(0, 10);
            tanggal_awal.val(formattedDate);
            tanggal_akhir.val(formattedDate);
            table.ajax.reload();
        })
        $("#search-1").on("click", function() {
            let selectedDate = new Date(tanggal_awal.val());
            selectedDate.setDate(selectedDate.getDate() - 1);
            let formattedDate = selectedDate.toISOString().slice(0, 10);
            tanggal_awal.val(formattedDate);
            tanggal_akhir.val(formattedDate);
            table.ajax.reload();
        })
        $("#search-2").on("click", function() {
            let selectedDate = new Date(tanggal_awal.val());
            selectedDate.setDate(selectedDate.getDate() - 2);
            let formattedDate = selectedDate.toISOString().slice(0, 10);
            tanggal_awal.val(formattedDate);
            tanggal_akhir.val(formattedDate);
            table.ajax.reload();
        })
        $("#search-3").on("click", function() {
            let selectedDate = new Date(tanggal_awal.val());
            selectedDate.setDate(selectedDate.getDate() - 3);
            let formattedDate = selectedDate.toISOString().slice(0, 10);
            tanggal_awal.val(formattedDate);
            tanggal_akhir.val(formattedDate);
            table.ajax.reload();
        })

        //Declarasi Tiap Form
        let nama = $("#nama")
        let jenis = $("#jenis")
        let jenis2 = $("#jenis2")
        let tanggal = $("#tanggal")
        let status = $("#status")
        let debit = $("#debit")
        let kredit = $("#kredit")
        let laba = $("#laba")

        //Apabila Tomboh Tambah Data di Click
        $("#tombol-input").on('click', function() {
            $(".saveAction").removeClass('d-none')
            $(".editAction").addClass('d-none')
            nama.val('');
            jenis.val('');
            jenis2.val('');
            tanggal.val($("#tanggal-awal").val());
            status.val('');
            debit.val('0');
            kredit.val('0');
            laba.val('');
            $(".id-last").html('')
        })

        //Apabila tombol x di modal di tekan
        $("#close").on('click', function() {
            nama.val('');
            jenis.val('');
            jenis2.val('');
            tanggal.val('');
            status.val('');
            debit.val('0');
            kredit.val('0');
            laba.val('');
            $(".id-last").html('')
        })

        //Apabila tombol close pada modal di tekan
        $("#batal").on('click', function() {
            nama.val('');
            jenis.val('');
            jenis2.val('');
            tanggal.val('');
            status.val('');
            debit.val('0');
            kredit.val('0');
            laba.val('');
            $(".id-last").html('')
        })

        //Action Tambah Data
        $('#saveBtn').on('click', function(e) {
            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function(index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $('#modal-form form').serialize()
                , url: "/transaksi"
                , type: "POST"
                , dataType: 'json'
                , success: function(data) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload()
                    Swal.fire({
                        position: 'top-end'
                        , icon: 'success'
                        , title: 'Data Berhasil Ditambahkan'
                        , showConfirmButton: false
                        , timer: 1500
                    })
                }
                , error: function(data) {
                    let error = data.responseJSON.errors
                    let nama = $("#nama")
                    let jenis = $("#jenis")
                    let jenis2 = $("#jenis2")
                    let tanggal = $("#tanggal")
                    let status = $("#status")
                    let debit = $("#debit")
                    let kredit = $("#kredit")
                    let laba = $("#laba")

                    let errorNama = $("#error-nama")
                    let errorJenis = $("#error-jenis")
                    let errorJenis2 = $("#error-jenis2")
                    let errorTanggal = $("#error-tanggal")
                    let errorStatus = $("#error-status")
                    let errorDebit = $("#error-debit")
                    let errorKredit = $("#error-kredit")
                    let errorLaba = $("#error-laba")

                    if (error.nama) {
                        nama.addClass('is-invalid')
                        errorNama.html(error.nama)
                    }
                    if (error.jenis) {
                        jenis.addClass('is-invalid')
                        errorJenis.html(error.jenis)
                    }
                    if (error.jenis2) {
                        jenis2.addClass('is-invalid')
                        errorJenis2.html(error.jenis2)
                    }
                    if (error.tanggal) {
                        tanggal.addClass('is-invalid')
                        errorTanggal.html(error.tanggal)
                    }
                    if (error.status) {
                        status.addClass('is-invalid')
                        errorStatus.html(error.status)
                    }
                    if (error.debit) {
                        debit.addClass('is-invalid')
                        errorDebit.html(error.debit)
                    }
                    if (error.kredit) {
                        kredit.addClass('is-invalid')
                        errorKredit.html(error.kredit)
                    }
                    if (error.laba) {
                        laba.addClass('is-invalid')
                        errorLaba.html(error.laba)
                    }
                }
            });
        });

        //Mengembalikan form ke semula apabila terjadi validasi
        nama.on('click', function() {
            nama.removeClass('is-invalid')
        })
        jenis.on('click', function() {
            jenis.removeClass('is-invalid')
        })
        jenis2.on('click', function() {
            jenis2.removeClass('is-invalid')
        })
        tanggal.on('click', function() {
            tanggal.removeClass('is-invalid')
        })
        status.on('click', function() {
            status.removeClass('is-invalid')
        })
        debit.on('click', function() {
            debit.removeClass('is-invalid')
        })
        kredit.on('click', function() {
            kredit.removeClass('is-invalid')
        })
        laba.on('click', function() {
            laba.removeClass('is-invalid')
        })

        //ACTION UPDATE DATA
        //Memanggil data yang di click
        $('#dataTable').on('click', '.edit-button', function() {
            $("input.money").simpleMoneyFormat({
                currencySymbol: "Rp"
                , decimalPlaces: 0
                , thousandsSeparator: "."
            , });
            let modal = $("#modal-form")
            let id = $(this).attr('data-id');
            let title = $("#staticBackdropLabel")
            let last_id = $("#id")
            let jenis = $("#jenis_transaksi")
            let laba = $("#laba")

            let sEdit = $(".editAction")
            let sSave = $(".saveAction")

            sSave.addClass('d-none')
            sEdit.removeClass('d-none')

            title.html('Edit Transaksi')
            modal.modal('show');

            $.ajax({
                data: {
                    id: id
                }
                , url: "{{ route('edit-transaksi') }}"
                , type: "GET"
                , dataType: 'json'
                , success: function(data) {
                    $(".id-last").html('<input type="hidden" name="id" value="' + data.id +
                        '">')
                    nama.val(data.nama)
                    $("#jenis").val(data.jenis)
                    jenis2.val(data.jenis2)
                    tanggal.val(data.tanggal)
                    status.val(data.status)
                    debit.val(data.debit)
                    kredit.val(data.kredit)
                    laba.val(data.laba)
                }
            })
        });

        //Ketik button update di tekan
        $('#editBtn').on('click', function(e) {

            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function(index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $('#modal-form form').serialize()
                , url: "{{ route('update-transaksi') }}"
                , type: "POST"
                , dataType: 'json'
                , success: function(data) {
                    console.log(data);
                    $('#modal-form').modal('hide');
                    table.ajax.reload()
                    Swal.fire({
                        position: 'top-end'
                        , icon: 'success'
                        , title: 'Data Berhasil Diedit'
                        , showConfirmButton: false
                        , timer: 1500
                    })
                }
                , error: function(data) {
                    let error = data.responseJSON.errors
                    let nama = $("#nama")
                    let jenis = $("#jenis")
                    let jenis2 = $("#jenis2")
                    let tanggal = $("#tanggal")
                    let status = $("#status")
                    let debit = $("#debit")
                    let kredit = $("#kredit")
                    let laba = $("#laba")

                    let errorNama = $("#error-nama")
                    let errorJenis = $("#error-jenis")
                    let errorJenis2 = $("#error-jenis2")
                    let errorTanggal = $("#error-tanggal")
                    let errorStatus = $("#error-status")
                    let errorDebit = $("#error-debit")
                    let errorKredit = $("#error-kredit")
                    let errorLaba = $("#error-laba")

                    if (error.nama) {
                        nama.addClass('is-invalid')
                        errorNama.html(error.nama)
                    }
                    if (error.jenis) {
                        jenis.addClass('is-invalid')
                        errorJenis.html(error.jenis)
                    }
                    if (error.jenis2) {
                        jenis2.addClass('is-invalid')
                        errorJenis2.html(error.jenis2)
                    }
                    if (error.tanggal) {
                        tanggal.addClass('is-invalid')
                        errorTanggal.html(error.tanggal)
                    }
                    if (error.status) {
                        status.addClass('is-invalid')
                        errorStatus.html(error.status)
                    }
                    if (error.debit) {
                        debit.addClass('is-invalid')
                        errorDebit.html(error.debit)
                    }
                    if (error.kredit) {
                        kredit.addClass('is-invalid')
                        errorKredit.html(error.kredit)
                    }
                    if (error.laba) {
                        laba.addClass('is-invalid')
                        errorLaba.html(error.laba)
                    }
                }
            });
        });

        //Menghapus data transaksi
        $('#dataTable').on('click', '.delete-button', function() {
            Swal.fire({
                title: 'Are you sure?'
                , text: "You won't be able to revert this!"
                , icon: 'warning'
                , showCancelButton: true
                , confirmButtonColor: '#3085d6'
                , cancelButtonColor: '#d33'
                , confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let token = $(this).attr('data-token');
                    let id = $(this).attr('data-id');
                    $.ajax({
                        data: {
                            id: id
                            , _token: token
                        }
                        , url: "{{ route('hapus-transaksi') }}"
                        , type: "POST"
                        , dataType: 'json'
                        , success: function(data) {
                            // console.log(data)
                            table.ajax.reload()
                            Swal.fire(
                                'Deleted!'
                                , 'Your file has been deleted.'
                                , 'success'
                            )
                        }
                    })
                }
            })

        });

    });


    //Mengisi Kolom laba sesuai dengan jenis2 yang di click
    function autofill() {
        let jenis = $('#jenis').val()
        // console.log(jenis)

        $.ajax({
            data: {
                jenis: jenis
            }
            , url: "{{ route('change-jenis') }}"
            , type: "GET"
            , dataType: 'json'
            , success: function(data) {
                $("#laba").val(data.laba);
            }
        , })
    }

    function autofill_2() {
        let status = $('#status').val()
        let jenis = $('#jenis').val()
        // console.log(jenis)

        $.ajax({
            data: {
                status: status
                , jenis: jenis
            }
            , url: "{{ route('change-status') }}"
            , type: "GET"
            , dataType: 'json'
            , success: function(data) {
                $("#laba").val(data.laba);
            }
        , })
    }

</script>
@endsection
