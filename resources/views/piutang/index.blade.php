@extends('layouts.main')
@section('container')
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
<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true" id="modal-form">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Masukan Nominal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form onSubmit="JavaScript:submitHandler()" action="javascript:void(0)">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" id="kredit-lama" name="kredit_lama">
                    @csrf
                    <div class="mb-3">
                        <label for="example-date" class="form-label">Kredit</label>
                        <input class="form-control" id="kredit" type="number" name="kredit" value="0">
                        <div class="invalid-feedback">
                            <strong>Error: </strong><span id="error-kredit"></span>
                        </div>
                    </div>
                    <div class="alert alert-primary" role="alert">
                        <small>Info Piutang: <strong id="nominal"></strong></small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="batal" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary editAction" id="editBtn">Edit</button>
            </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        //Inialisasi Datatables
        let table = $('#dataTable').DataTable({
            "processing": true,
            "responsive": true,
            "searching": true,
            "bLengthChange": true,
            "info": false,
            "ordering": true,
            "serverSide": true,
            "ajax": "{{ route('piutang.dataTables') }}",
            "columns": [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                },
                {
                    "data": 'nama_t'
                },
                {
                    "data": 'jenis_t'
                },
                {
                    "data": 'tanggal_t'
                },
                {
                    "data": 'status'
                },
                {
                    "data": 'debit_t'
                },
                {
                    "data": 'kredit_t'
                },
                {
                    "data": 'action',
                    "orderable": true,
                    "searchable": true
                },
            ],
            "order": [
                [0, 'desc']
            ]

        });
        //Action Lunas Debit
        $('#dataTable').on('click', '.debit-button', function () {
            Swal.fire({
                title: 'Yakin Lunas Debit?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Lunaskan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let token = $(this).attr('data-token');
                    let id = $(this).attr('data-id');
                    $.ajax({
                        data: {
                            id: id,
                            _token: token
                        },
                        url: "{{ route('lunas-debit') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            // console.log(data)
                            table.ajax.reload()
                            Swal.fire(
                                'Lunas!',
                                'Piutang  telah lunas.',
                                'success'
                            )
                        }
                    })
                }
            })

        });

        $("#close").on('click', function () {
            $("#id").val('');
            $("#kredit").val('0');
        })

        $("#batal").on('click', function () {
            $("#id").val('');
            $("#kredit").val('0');
        })

        //Action Lunas Kredit
        //memasukan data ke modal
        $('#dataTable').on('click', '.kredit-button', function () {
            let id = $(this).attr('data-id');
            let nominal = $(this).attr('data-nominal');
            let kredit = $(this).attr('data-kredit');
            $('#kredit-lama').val(kredit)
            $('#nominal').html(nominal);
            $("#id").val(id);
            $("#modal-form").modal('show');
        });

        $("#kredit").on('click', function () {
            $("#kredit").removeClass('is-invalid');
        })

        //Actionnya

        $('#editBtn').on('click', function (e) {
            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function (index, obj) {
                data[obj.name] = obj.value;
            });
            if (data.kredit < data.kredit_lama) {
                let kredit = $("#kredit")
                let errorkredit = $("#error-kredit")
                kredit.addClass('is-invalid')
                errorkredit.html('Jumlah nominal tidak boleh kurang dari piutang!')
            } else {
                $.ajax({
                    data: $('#modal-form form').serialize(),
                    url: "{{ route('lunas-kredit') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        $('#modal-form').modal('hide');
                        table.ajax.reload()
                        Swal.fire(
                            'Lunas!',
                            'Piutang  telah lunas.',
                            'success'
                        )
                    },
                    error: function (data) {
                        let error = data.responseJSON.errors
                        let kredit = $("#kredit")

                        let errorkredit = $("#error-kredit")

                        if (error.kredit) {
                            kredit.addClass('is-invalid')
                            errorkredit.html(error.kredit)
                        }
                    }
                });
            }
        });
    });

</script>
@endsection
