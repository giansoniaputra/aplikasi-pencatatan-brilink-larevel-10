@extends('layouts.main')

@section('container')
<div class="flash-data" data-flashdata="{{ session('success') }}"></div>
<div class="col">
    <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#staticBackdrop">
        <span class="icon text-white-50">
            <i class="fas fa-flag"></i>
        </span>
        <span class="text">Edit Modal</span>
    </button>
</div>
<div class="col-xl-3 col-md-6 mb-4 mt-3">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Modal Saat Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">@currency($modal->modal)</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Input Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/modal/{{ $modal->id }}" method="post">
                    @method('put')
                    @csrf
                    <div class="form-group">
                        <label for="modal">Modal</label>
                        <input type="text" class="form-control" id="modal" name="modal" value="{{ $modal->modal }}"
                            onkeyup="rupiah()">
                    </div>
                    <small class="ml-1" id="nominal">
                        @currency($modal->modal)
                    </small>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Modal</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    function rupiah() {
        let modal = document.querySelector("#modal").value;
        let nominal = $('#nominal');

        let number_string = modal.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += 'Rp. ' + separator + ribuan.join('.');
        }
        nominal.html(rupiah);
    }

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
