@extends('layouts.main')

@section('title', 'Satuan')

@section('content')
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-default">
                Tambah Satuan
            </button>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama Satuan</th>
                        <th>Alias</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($satuans as $satuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $satuan->nama }}</td>
                            <td>{{ $satuan->alias }}</td>
                            <td><button class="btn btn-danger button-hapus" data-id="{{ $satuan->id }}">Hapus</button></td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="satuanForm" action="">
                        <div class="form-group">
                            <label>Nama</label>
                            <input id="nama" name="nama" type="text" class="form-control"
                                placeholder="Input nama satuan">
                        </div>
                        <div class="form-group">
                            <label>Alias</label>
                            <input id="alias" type="text" name="alias" class="form-control"
                                placeholder="Input alias satuan">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#submit").click(function() {
                var nama = $("#nama").val();
                var alias = $("#alias").val();

                console.log(nama, alias)


                var data = {
                    nama: nama,
                    alias: alias
                };


                $.ajax({
                    url: "{{ route('satuan-store') }}",
                    type: "POST",
                    data: data,
                    success: function(response) {

                        console.log(response);
                        $(".modal").modal("hide");

                        $.ajax({
                            url: "{{ route('satuan-index') }}",
                            type: "GET",
                            success: function(response) {

                                $("table tbody").html($(response).find(
                                    "table tbody").html());

                                var nama = $("#nama").val("");
                                var alias = $("#alias").val("");
                            },
                            error: function(xhr) {

                                console.log(xhr
                                    .responseText
                                );
                            }
                        });

                    },
                    error: function(xhr) {

                        console.log(xhr
                            .responseText);
                    }
                });
            });
            // Fungsi untuk menghapus data
            $("table").on("click", ".button-hapus", function() {
                var satuanId = $(this).data("id");

                // Mengirim permintaan delete ke backend menggunakan Ajax
                $.ajax({
                    url: "/satuan/" + satuanId,
                    type: "DELETE",
                    success: function(response) {
                        // Menangani respon dari backend setelah berhasil dihapus
                        console.log(response); // Misalnya, menampilkan respon di konsol

                        // Menghapus baris dari tabel
                        $.ajax({
                            url: "{{ route('satuan-index') }}",
                            type: "GET",
                            success: function(response) {

                                $("table tbody").html($(response).find(
                                    "table tbody").html());
                            },
                            error: function(xhr) {
                                console.log(xhr
                                    .responseText
                                );
                            }
                        });
                    },
                    error: function(xhr) {
                        // Menangani error jika terjadi kesalahan dalam menghapus data dari backend
                        console.log(xhr
                            .responseText); // Misalnya, menampilkan pesan error di konsol
                    }
                });
            });
            //
        });
    </script>

@endsection
