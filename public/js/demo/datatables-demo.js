// Call the dataTables jQuery plugin
$(document).ready(function () {
    $('#dataTable').DataTable({
        "processing": true,
        "responsive": true,
        "searching": false,
        "bLengthChange": false,
        "info": false,
        "ordering": true,
        "serverSide": true,
        "ajax": {
            "url": "{{ }}",
            "type": "GET"
        },
        "coloum": [
          { // mengambil & menampilkan kolom sesuai tabel database
            "data": 'id'
          },
          {
            "data" : 'jenis_transaksi'
          },
          {
            "data" : 'laba'
          }
        ]

    });

});
