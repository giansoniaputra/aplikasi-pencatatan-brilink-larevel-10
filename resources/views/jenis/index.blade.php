@extends('layouts.main')
@section('container')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="flash-data" data-flashdata="{{ session('success') }}"></div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button type="button" class="btn btn-primary btn-icon-split tambah-data" data-toggle="modal"
            data-target="#modal-form">
            <span class="icon text-white-50">
                <i class="fas fa-flag"></i>
            </span>
            <span class="text">Tambah Jenis Transaksi</span>
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jenis Tagihan</th>
                        <th>Laba</th>
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
<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Input Jenis Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)">
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" class="form-control" name="jenis_transaksi" id="jenis_transaksi">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-jenis"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="laba">Laba</label>
                        <input type="text" class="form-control" name="laba" id="laba">
                        <div class="invalid-feedback">
                            <strong>Error:</strong><span id="error-laba"></span>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveBtn" id="saveBtn">Create</button>
                <button type="button" class="btn btn-primary editBtn" id="editBtn">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Edit
<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true" id="modal-form-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Jenis Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)">
@csrf
                    <div class="form-group">
                        <label for="jenis_transaksi">Jenis Transaksi</label>
                        <input type="text" class="form-control" name="jenis_transaksi" id="jenis_transaksi-edit">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-jenis-edit"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="laba">Laba</label>
                        <input type="text" class="form-control" name="laba" id="laba-edit">
                        <div class="invalid-feedback">
                            <strong>Error:</strong><span id="error-laba-edit"></span>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
            </div>
            </form>
        </div>
    </div>
</div> --}}


<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function () {
        let table = $('#dataTable').DataTable({
            "processing": true,
            "responsive": true,
            "searching": true,
            "bLengthChange": true,
            "info": false,
            "ordering": true,
            "serverSide": true,
            "ajax": "{{ route('jenis.dataTables') }}",
            "columns": [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": 'jenis'
                },
                {
                    "data": 'labaTrans'
                },
                {
                    "data": 'action',
                    "orderable": true,
                    "searchable": true
                },
            ],
            "order": [[1, 'asc']]

        });

        $("#close").on('click', function () {
            $("#jenis_transaksi").val('');
            $("#laba").val('');
        })

        //TAMBAH DATA JENIS TRANSAKSI
        $('.tambah-data').on('click', function () {
            let title = $("#staticBackdropLabel")
            let sEdit = $(".editBtn")
            let sSave = $(".saveBtn")

            $("#jenis_transaksi").val('');
            $("#laba").val('');

            sEdit.addClass('d-none')
            sSave.removeClass('d-none')
            title.html('Tambah Jenis Transaksi')
        })

        $('#saveBtn').on('click', function (e) {

            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function (index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $('#modal-form form').serialize(),
                url: "{{ route('create_jenis') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Berhasil Ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function (data) {
                    let error = data.responseJSON.errors
                    let jenis = $("#error-jenis")
                    let laba = $("#error-laba")
                    if (error.jenis_transaksi && error.laba) {
                        $("#jenis_transaksi").addClass('is-invalid')
                        jenis.html(error.jenis_transaksi[0])
                        $("#laba").addClass('is-invalid')
                        laba.html(error.laba[0])
                    } else if (error.laba) {
                        $("#laba").addClass('is-invalid')
                        laba.html(error.laba[0])
                    } else if (error.jenis_transaksi) {
                        $("#jenis_transaksi").addClass('is-invalid')
                        jenis.html(error.laba[0])
                    }
                }
            });
        });

        $("#jenis_transaksi").on('click', function () {
            $("#jenis_transaksi").removeClass('is-invalid');
        })

        $("#laba").on('click', function () {
            $("#laba").removeClass('is-invalid');
        })


        //EDIT DATA JENIS TRANSAKSI
        $('#dataTable').on('click', '.edit-button', function () {
            let modal = $("#modal-form")
            let id = $(this).attr('data-id');
            let title = $("#staticBackdropLabel")
            let last_id = $("#id")
            let jenis = $("#jenis_transaksi")
            let laba = $("#laba")

            let sEdit = $(".editBtn")
            let sSave = $(".saveBtn")

            sSave.addClass('d-none')
            sEdit.removeClass('d-none')

            title.html('Edit Jenis Transaksi')
            modal.modal('show');

            $.ajax({
                data: {
                    id: id
                },
                url: "{{ route('edit_jenis') }}",
                type: "GET",
                dataType: 'json',
                success: function (data) {
                    last_id.val(data.id);
                    jenis.val(data.jenis_transaksi);
                    laba.val(data.laba)
                    // $('#modal-form').modal('hide');
                    // table.ajax.reload()
                    // Swal.fire({
                    //     position: 'top-end',
                    //     icon: 'success',
                    //     title: 'Data Berhasil Ditambahkan',
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // })
                }
            })

            // Lakukan sesuatu dengan nilai ID yang didapatkan
        });

        $('#editBtn').on('click', function (e) {
            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function (index, obj) {
                data[obj.name] = obj.value;
            });
            $.ajax({
                data: $('#modal-form form').serialize(),
                url: "{{ route('update_jenis') }}",
                type: "POST",
                dataType: 'json',
                success: function (data) {
                    $('#modal-form').modal('hide');
                    table.ajax.reload()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Berhasil Diedit',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function (data) {
                    let error = data.responseJSON.errors
                    let jenis = $("#error-jenis")
                    let laba = $("#error-laba")
                    if (error.jenis_transaksi && error.laba) {
                        $("#jenis_transaksi").addClass('is-invalid')
                        jenis.html(error.jenis_transaksi[0])
                        $("#laba").addClass('is-invalid')
                        laba.html(error.laba[0])
                    } else if (error.laba) {
                        $("#laba").addClass('is-invalid')
                        laba.html(error.laba[0])
                    } else if (error.jenis_transaksi) {
                        $("#jenis_transaksi").addClass('is-invalid')
                        jenis.html(error.laba[0])
                    }
                }
            });
        });
    });

    const flashData = $('.flash-data').data('flashdata');

    if (flashData) {
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: flashData,
            showConfirmButton: false,
            timer: 1500
        })
    }

</script>
@endsection
