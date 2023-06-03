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
                        <label>Scan</label>
                        <div class="position-relative">
                            <input id="inputscan" name="inputscan" type="text" class="form-control"
                                placeholder="Mesin Scanner atau Input Kode Manual">
                            <div class="dropdown-menu-kasir dropdown-menu w-100" id="menukasir"
                                aria-labelledby="dropdownMenuButton">
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Gambar</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Satuan</th>
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
            $('#inputscan').autocomplete({
                source: [],
                minLength: 0
            }).autocomplete('disable');

        var dataArray = [];

            $("#inputscan").on("keyup", function() {
                var searchText = $(this).val();
                // console.log(dataArray)
                $.ajax({
                    url: "/kasir/search",
                    method: "POST",
                    data: {
                        search: searchText
                        // dataarray: dataArray
                    },
                    success: function(response) {
                        var results = response.results;

                        if (results.length > 0) {
                            var dropdownMenu = $(".dropdown-menu-kasir")
                            dropdownMenu.empty();
                            if (searchText.trim() === "") {
                                $(".dropdown-menu-kasir").hide();
                                return;
                            }

                            $.each(results, function(index, result) {
                                var item =
                                    `<p class="dropdown-item" data-id="${result.id}">${result.nama}</p>`;
                                dropdownMenu.append(item);
                            });
                            dropdownMenu.show();
                        } else {
                            $(".dropdown-menu-kasir").hide();
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $(document).on("click", ".dropdown-item", function() {
                var id = $(this).data("id");
                $("#inputscan").val("");
                $.ajax({
                    url: "/kasir/" + id,
                    method: "GET",
                    success: function(response) {
                        var result = response.results
                        // var result2 = response.results2
                        // console.log(result2)
                        // console.log(response)
                        $(".dropdown-menu-kasir").hide();
                        var newRow = `<tr>
                            <td>${result.id}</td>
                            <td><img src="{{ asset('storage/${result.gambar}') }}" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;"></td>
                            <td>${result.kode}</td>
                            <td>${result.nama}</td>
                            <td>${result.satuans.alias}</td>
                            <td></td>
                            <td></td>
                            <td><button class="btn btn-danger button-hapus">Hapus</button></td>
                            </tr>`;

                        $("table tbody").prepend(newRow);
                        dataArray.push(result.id);
                        // console.log(dataArray)
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
            $("table tbody").on("click", ".button-hapus", function() {
                $(this).closest("tr").remove();
                // also delete from array data-id from array data

                var id = $(this).closest("tr").find("td:first").text();

                for (var i = 0; i < dataArray.length; i++) {
                    if (dataArray[i] == id) {
                        dataArray.splice(i, 1);
                    }
                }

                $("#inputscan").focus();
            });

        });
</script>
<style>
    .search-item {
        padding: 5px;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: skyblue;
    }
</style>
@endsection
