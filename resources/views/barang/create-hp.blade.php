@extends('layouts.main')

@section('title', 'Tambah Barang Via HP')

<style>
</style>

@section('content')
<div class="card">
    <div class="card-body">
        <div class="toast-container">
        </div>
        <div class="mb-3" id="reader" width="600px"></div>
        <div class="form-group">
            <label>Scan Barcode</label>
            <input id="kode" name="kode" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label>Nama Barang</label>
            <input id="nama" name="nama" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label>Satuan Barang</label>
            <select class="form-control select2" style="width: 100%;" name="satuan">
                <option selected disabled></option>
                @foreach ($satuans as $satuan)
                <option value="{{ $satuan->id }}">{{ $satuan->nama }} ||
                    {{ $satuan->alias }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <div class="my-wrapper">
                <div class="input-group mb-2 minhar">
                    <span class="input-group-addon">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Harga</span>
                        </div>
                    </span>
                    <input style="width: 100px" name="harga[]" id="harga" type="text" class="form-control harga"
                        placeholder="Harga" />
                    {{-- ICON --}}
                    <span class="input-group-addon">
                        <div class="input-group-prepend" style="height: 100%">
                            <span class="input-group-text icon" id="basic-addon1"><i class="fa fa-trash"></i>
                            </span>
                        </div>
                    </span>
                    {{-- ICON --}}
                </div>
            </div>
            <div class="d-flex flex-column align-items-center">
                <button type="button" id="tambahharga" class="btn btn-primary mt-3 harga-minal">Tambah Harga</button>
            </div>
        </div>
        <div class="text-center">
            <div class="image-container" id="image-container">
                <img src="" name="gambar" id="gambar-preview" style="display: none;" alt="Preview Gambar"
                    class="mx-auto">
            </div>
        </div>
        <div class="form-group">
            <label>Gambar Barang</label>
            <input class="form-control" type="file" id="gambar-input">
        </div>

        <hr>
        <div class="d-flex flex-column align-items-center">
            <button type="button" id="prosesbarang" class="btn btn-success mt-3">
                Proses Barang
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function playSuccessSound() {
            const successSound = new Audio("{{ asset('assets/sound/mixkit-game-notification-wave-alarm-987.wav') }}");
            console.log(successSound);
            successSound.play();
        }

        function onScanSuccess(decodedText, decodedResult) {
            $("#kode").val(decodedText);
            playSuccessSound();
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 100
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        $(document).ready(function() {
            $('.harga').mask('000.000.000', {
                reverse: true
            });

            // Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            })

            $("#kode").focus();

            $('#image-container').hide();

            // PREWIVEW GAMBAR

            $('#gambar-input').change(function() {
                let file = $(this)[0].files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        // Menampilkan preview gambar
                        $('#gambar-preview').attr('src', e.target.result);
                        $('#gambar-preview').show();
                        $('#image-container').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#gambar-preview').attr('src', '');
                    $('#image-container').hide();
                }
            });

            // HARGA DAN MIN
            $('#tambahharga').click(function() {
                var inputGroup = $(`
                    <div class="input-group mb-2 minhar">
                        <span class="input-group-addon">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Harga</span>
                            </div>
                        </span>
                        <input style="width: 100px" name="harga[]" id="harga" type="text" class="form-control harga"
                            placeholder="Harga" />
                        {{-- ICON --}}
                        <span class="input-group-addon">
                            <div class="input-group-prepend" style="height: 100%">
                                <span class="input-group-text icon" id="basic-addon1"><i class="fa fa-trash"></i>
                                </span>
                            </div>
                        </span>
                        {{-- ICON --}}
                    </div>
                            `);
                inputGroup.find('.harga').each(function() {
                    $(this).mask('000.000.000', {
                        reverse: true
                    });
                });

                $(".my-wrapper").append(inputGroup);
            });

            // HAPUS HARGA
            $(document).on('click', '.input-group-addon .icon', function() {
                $(this).closest('.input-group').remove();
            });

            $(document).on('click', '#prosesbarang', function(event) {
                var kode = $("#kode").val();
                var nama = $("#nama").val();
                var satuanid = $('select[name="satuan"]').val();
                var arrayminhar = [];
                var src = $("#gambar-preview").attr("src");
                if (src == undefined) {
                src = "https://static.vecteezy.com/system/resources/previews/005/337/799/original/icon-image-not-found-free-vector.jpg";
                }

                $('.minhar').each(function() {
                    var harga = $(this).find('.harga').val();
                    var data = {
                        harga: harga
                    };
                    arrayminhar.push(data);
                });

                // console.log(kode, nama, satuanid, arrayminhar, src);
                $.ajax({
                    url: '{{ route('barang-store-hp') }}',
                    method: 'POST',
                    data: {
                        kode: kode,
                        nama: nama,
                        satuanid: satuanid,
                        minhar: arrayminhar,
                        src: src
                    },
                    success: function(response) {
                        console.log('Data berhasil dikirim:', response);
                        showToast(response.message, response.status);
                        $("#kode").val('');
                        $("#nama").val('');
                        $("#kode").focus();
                    },
                    error: function(xhr, status, error) {
                        var errorResponse = JSON.parse(xhr.responseText);
                        var errors = errorResponse.error;
                        var errorMessages = '<ul>';

                        // Loop melalui setiap kunci kesalahan
                        for (var key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                // Ambil pesan kesalahan untuk kunci tersebut
                                var errorMessage = errors[key][0];
                                errorMessages += '<li>' + errorMessage + '</li>';
                            }
                        }

                        errorMessages += '</ul>';

                        showToast(errorMessages, 'error');
                        $("#kode").focus();
                    }

                });
            });


            // TOAST
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
                // Buat elemen jQuery dari toast
                var $toast = $(toast);

                // Tambahkan toast ke dalam container
                toastContainer.append($toast);

                // Inisialisasi objek Toast Bootstrap
                var toast = new bootstrap.Toast($toast[0]);

                // Tampilkan toast
                toast.show();


                // Sembunyikan toast setelah 3 detik
                setTimeout(function() {
                    toast.hide();
                    setTimeout(function() {
                        $toast.remove();
                    }, 500); // Waktu tambahan untuk animasi sebelum menghapus elemen
                }, 15000);
            }
        });
</script>
<style>
    /* TOAST */

    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }

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

    .input-group .icon {
        background-color: red;
        color: white;
    }

    .image-container {
        width: 200px;
        height: 200px;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>
@endsection
