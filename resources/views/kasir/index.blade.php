@extends('layouts.main')

@section('title', 'Kasir')

@section('content')
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-body">
            <div class="toast-container">
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-9 order-2 order-md-1">
                    <div class="form-group">
                        <label>Scan Barang : </label>
                        {{-- FORM --}}
                        <form id="form-kasir">
                            <input id="scanner" name="scan" type="text" placeholder="Gunakan mesin scanner"
                                class="form-control">
                        </form>
                        {{-- <input id="scanner" name="scanner" type="text" placeholder="Gunakan mesin scanner"
                            class="form-control"> --}}
                    </div>
                    <div class="form-group">
                        <label>Cari Nama atau Kode Manual : </label>
                        <div class="position-relative">
                            <input id="inputscan" name="inputscan" type="text" class="form-control">
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
                                <th style="width: 13%;">Qty</th>
                                <th>Harga (pcs)</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-12 col-lg-3 order-1 order-md-2">
                    <div class="form-group">
                        <label>Uang Pembeli : </label>
                        <div class="position-relative">
                            <input id="inputuang" name="inputuang" type="text" class="form-control">
                        </div>
                    </div>
                    <h2>Kembalian :</h2>
                    <div class="bg-primary py-2 px-3 rounded-sm">
                        <h2 class="mb-0 kembalian">
                            0
                        </h2>
                    </div>
                    <h2>Total Transaksi :</h2>
                    <div class="bg-green py-2 px-3 rounded-sm">
                        <h2 class="mb-0 total">
                            0
                        </h2>
                    </div>
                    <div class="d-flex flex-column align-items-center">
                        <button type="button" id="proses-transaksi" class="btn btn-success mt-3">Proses
                            Transaksi
                        </button>
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

        // FOCUS SCANNER START
        $("#scanner").focus();
        // FOCUS SCANNER END

        // GLOBAL DATA START
        var dataArray = [];
        var dataObject = [];
        // GLOBAL DATA END

        // INPUT UANG MASK START
        $("#inputuang").mask('000.000.000', {
            reverse: true
        });
        // INPUT UANG MASK END

        // INPUT UANG KEYUP START
        $("#inputuang").on("keyup", function() {
            var inputuang = $(this).val().replace(/\./g, "");
            var total = $(".total").text().replace(/\./g, "");

            var kembalian = 0;

            if (Number(total) < Number(inputuang) && Number(total) > 0) {
                kembalian = Number(inputuang) - Number(total);
            }

            $(".kembalian").text(kembalian).mask('000.000.000', {
                reverse: true
            });
        });
            // INPUT UANG KEYUP END


        // INPUT SCAN KEYUP START
        $("#form-kasir").on("submit", function(e) {
            e.preventDefault();
            var scanValue = $("#scanner").val();

            $.ajax({
                url: "{{ route('kasir-scan') }}",
                method: "POST",
                data: {
                    scan: scanValue,
                    dataarray: dataArray
                },
                success: function(response) {
                    $("#scanner").focus();
                    var result = response.results

                    // if result null
                    if (result == null) {
                        return $("#scanner").val('');
                    }

                    var hargaPertama = result.hargas[0]
                    console.log(result)
                    dataArray.push(result.id);

                    var newRow = `<tr>
                        <td>${result.id}</td>
                        <td><img id="gambar" src="" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;"></td>
                        <td>${result.kode}</td>
                        <td>${result.nama}</td>
                        <td>${result.satuans.alias}</td>
                        <td style="width: 50px;"><input style="width: 50px;" type="text" id="quantity" class="qty" name="quantity" value="1"
                                min="1"></td>
                        <td class="hargaPertama" data-id=${hargaPertama.id}>${hargaPertama.harga}</td>
                        <td class="total-harga"></td>
                        <td><button class="btn btn-danger button-hapus">Hapus</button></td>
                    </tr>`;

                    var imageUrl = `{{ asset('storage/${result.gambar}') }}`;
                    var image = new Image();
                    image.src = imageUrl;
                    image.onload = function() {
                        console.log("Gambar tersedia");
                        $("#gambar").last().attr("src", imageUrl);
                    };
                    image.onerror = function() {
                        console.log("Gambar tidak tersedia");
                        var defaultImageUrl = result.gambar
                        $("#gambar").last().attr("src", defaultImageUrl);
                    }

                    $("table tbody").prepend(newRow);

                    $(".hargaPertama").mask('000.000.000', {
                    reverse: true
                    });

                    $("#scanner").focus();
                    $("#scanner").val('');
                    $(".qty").trigger("keyup");
                    $("#inputuang").trigger("keyup");
                    hitungTotal();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
                });
            });
            // INPUT SCAN KEYUP END


            // INPUT SCAN KEYUP START
            $("#inputscan").on("keyup", function() {
                var searchText = $(this).val();

                clearTimeout($(this).data("timeout"));
                var timeout = setTimeout(function() {
                $.ajax({
                    url: "/kasir/search",
                    method: "POST",
                    data: {
                        search: searchText,
                        dataarray: dataArray
                    },
                    success: function(response) {
                        console.log(response.results)
                        var results = response.results;
                        if (results.length > 0) {
                            var dropdownMenu = $(".dropdown-menu-kasir")
                            dropdownMenu.empty();
                            if (searchText.trim() === "") {
                                $(".dropdown-menu-kasir").hide();
                                return;
                            }
                            $.each(results, function(index, result) {
                                var item = `<p class="dropdown-item" data-id="${result.id}">${result.nama}</p>`;
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
                }, 250);

                $(this).data("timeout", timeout);
            });
            // INPUT SCAN KEYUP END

            // INPUT SCAN FOCUSOUT START
            $(document).on("mousedown", ".dropdown-item", function() {
                var id = $(this).data("id");

                $("#inputscan").val("");
                $.ajax({
                    url: "/kasir/" + id,
                    method: "GET",
                    success: function(response) {
                        $(".dropdown-menu-kasir").hide();
                        $("#inputscan").focus();
                        var result = response.results
                        var hargaPertama = result.hargas[0]
                        // console.log(hargaPertama)
                        dataArray.push(result.id);

                        var newRow = `<tr>
                            <td>${result.id}</td>
                            <td><img id="gambar" src="" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;"></td>
                            <td>${result.kode}</td>
                            <td>${result.nama}</td>
                            <td>${result.satuans.alias}</td>
                            <td style="width: 50px;"><input style="width: 50px;" type="text" id="quantity" class="qty" name="quantity" value="1" min="1"></td>
                            <td class="hargaPertama" data-id=${hargaPertama.id}>${hargaPertama.harga}</td>
                            <td class="total-harga"></td>
                            <td><button class="btn btn-danger button-hapus">Hapus</button></td>
                            </tr>`;

                        var imageUrl = `{{ asset('storage/${result.gambar}') }}`;
                        var image = new Image();
                        image.src = imageUrl;
                        image.onload = function() {
                                console.log("Gambar tersedia");
                                $("#gambar").last().attr("src", imageUrl);
                        };
                        image.onerror = function() {
                            console.log("Gambar tidak tersedia");
                                var defaultImageUrl = result.gambar
                            $("#gambar").last().attr("src", defaultImageUrl);
                        }

                        $("table tbody").prepend(newRow);

                        $(".hargaPertama").mask('000.000.000', {
                        reverse: true
                        });

                        $("#inputuang").trigger("keyup");
                        $(".qty").trigger("keyup");
                        hitungTotal();

                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
            // INPUT SCAN FOCUSOUT END

            // BUTTON HAPUS START
            $("table tbody").on("click", ".button-hapus", function() {
                $(this).closest("tr").remove();

                var id = $(this).closest("tr").find("td:first").text();

                for (var i = 0; i < dataArray.length; i++) {
                    if (dataArray[i] == id) {
                        dataArray.splice(i, 1);
                    }
                }
                $("#scanner").focus();
                hitungTotal();

            });
            // BUTTON HAPUS END

            // QTY START
            $("table tbody").on("keyup", ".qty", function() {
                var qty = $(this).val();
                var harga = $(this).closest("tr").find("td:eq(6)").text().replace(/\./g, '');
                var total = qty * harga;
                $(this).closest("tr").find("td:eq(7)").text(total)
                .mask('000.000.000', { reverse: true });

                hitungTotal();
            });
            // QTY END

            // HITUNG TOTAL START
            function hitungTotal() {
                var total = 0;
                $("table tbody tr").each(function(index, tr) {
                    var subtotal = $(this).find("td:eq(7)").text().replace(/\./g, '');
                    total += parseFloat(subtotal);
                });
                $(".total").text(total).mask('000.000.000', {
                        reverse: true
                });

                $("#inputuang").trigger("keyup");
            }
            // HITUNG TOTAL END

            // PROSES TRANSAKSI START
            $("#proses-transaksi").on("click", function() {
                var total = $(".total").text();
                var uangpembeli = $("#inputuang").val();
                var kembalian = $(".kembalian").text();
                console.log(total)
                prosesTransaksi();
                console.log(dataTransaksi)
                $.ajax({
                    url: "/transaksi",
                    method: "POST",
                    data: {
                        dataTransaksi: dataTransaksi,
                        total: total,
                        uangpembeli: uangpembeli,
                        kembalian: kembalian
                    },
                    success: function(response) {
                        console.log(response)
                        // CLEAR DATA
                        $("#inputuang").val("");
                        $("table tbody").empty();
                        $(".total").text("0");
                        $("#inputscan").val("");
                        $("#inputuang").trigger("keyup");
                        $(".dropdown-menu-kasir").hide();
                        dataTransaksi = [];
                        dataArray = [];
                        $("#scanner").focus();
                        showToast(response.message, response.status);
                    },
                    error: function(xhr) {
                        $("#scanner").focus();
                        console.log(xhr.responseText);
                    }
                });
            });
            // PROSES TRANSAKSI END

        var dataTransaksi = [];

        // PROSES TRANSAKSI START
        function prosesTransaksi() {
            $("table tbody tr").each(function(index, tr) {
            var id = $(this).find("td:eq(0)").text();
            var qty = $(this).find("td:eq(5) input").val();
            var harga_id = $(this).find("td:eq(6)").data("id");
            var totalHarga = $(this).find("td:eq(7)").text();
            var obj = {
                id: id,
                qty: qty,
                total: totalHarga,
                harga_id: harga_id,
            };
            dataTransaksi.push(obj);
            });
        }
        // PROSES TRANSAKSI END

        // TOAST START
            function showToast(message, type) {
                var toastContainer = $(".toast-container");

                var toast = `
                    <div class="toast ${type === 'success' ? 'bg-green' : 'bg-red'}" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                        <strong class="me-auto">${type === 'success' ? 'success' : 'error'}</strong>
                        </div>
                        <div class="toast-body">
                            ${message}
                        </div>
                    </div>
                `
                var $toast = $(toast);
                toastContainer.append($toast);
                var toast = new bootstrap.Toast($toast[0]);
                toast.show();

                setTimeout(function() {
                    toast.hide();
                    setTimeout(function() {
                        $toast.remove();
                    }, 500);
                }, 15000);
            }
        // TOAST END


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

    /* TOAST START */
    .toast-body ul {
        list-style-type: disc;
        margin: 0;
        padding: 0 0 0 20px;
    }

    .toast-body ul li {
        margin-bottom: 5px;
    }

    .bg-green {
        background-color: greenyellow;
    }

    .bg-red {
        background-color: red;
    }

    .toast {
        opacity: 0;
        animation: fade-in 2s ease-in-out forwards;
    }

    .fade {
        transition-duration: 4s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

    /* TOAST END */
</style>
@endsection
