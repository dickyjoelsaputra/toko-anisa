@extends('layouts.main')

@section('title', 'Kasir')

@section('content')
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="form-group">
                            <form id="scanForm" action="">
                                <label>Scan</label>
                                <input id="inputscan" type="text" class="form-control"
                                    placeholder="Mesin Scanner atau Input Kode Manual">
                            </form>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th style="width: 13%;">Qt</th>
                                    <th>Harga (pcs)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <h2>Total :</h2>
                        <div class="bg-primary py-2 px-3 rounded-sm">
                            <h2 class="mb-0">
                                $80.00
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#inputscan").focus();
            $("#scanForm").submit(function(event) {
                event.preventDefault(); // Mencegah pengiriman formulir secara normal

                var inputValue = $("#inputscan").val();
                console.log(inputValue);
                $("#inputscan").val("");

                // Menambahkan baris baru ke dalam tabel
                var newRow = $("<tr>");
                newRow.append("<td>2.</td>");
                newRow.append(
                    '<td><img src="https://cf.shopee.co.id/file/17a4bc9e226288c6d065c3bb54a33006" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;"></td>'
                );
                newRow.append("<td>" + inputValue + "</td>");
                newRow.append("<td>Nama Produk Baru</td>");
                newRow.append('<td><input type="number" class="form-control" value="1" min="1"></td>');
                newRow.append("<td>Rp. 50.000</td>");
                newRow.append('<td><button class="btn btn-danger button-hapus">Hapus</button></td>');

                $("table tbody").prepend(newRow);
            });

            $("table tbody").on("click", ".button-hapus", function() {
                $(this).closest("tr").remove();
                $("#inputscan").focus();
            });
        });
    </script>
@endsection
