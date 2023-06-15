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
            <label>Harga Barang</label>
            <input id="harga" name="harga" type="text" class="form-control">
        </div>

        <div class="row">
            <div class="col-md-6">
                <h3>Akses Kamera</h3>
                <div id="cameraView" class="camera-container">
                    <video id="videoElement"></video>
                </div>
                <div class="d-flex flex-column align-items-center">
                    <button type="button" id="captureBtn" class="btn btn-primary mt-3">Ambil
                        Foto</button>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Hasil Jepretan</h3>
                <div id="photoPreview" class="photo-container"></div>
                <div class="d-flex flex-column align-items-center">
                    <button type="button" id="deleteBtn" class="btn btn-danger mt-3 mx-auto"
                        style="display: none;">Hapus
                        Foto</button>
                </div>
            </div>
        </div>


        <hr>
        <div class="d-flex flex-column align-items-center mb-5">
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
    // START SCANNER
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

        // END SCANNER


        // =============== KAMERA START =======================
        // Akses kamera
        navigator.mediaDevices.getUserMedia({
        video: { facingMode: 'environment' }
        })
        .then(function(stream) {
        var video = document.getElementById('videoElement');
        video.autoplay = true;
        video.srcObject = stream;
        })
        .catch(function(error) {
        console.log('Error accessing camera: ' + error.message);
        });

        // Ambil foto
        $('#captureBtn').click(function() {
        var video = document.getElementById('videoElement');
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');

        var width = video.videoWidth;
        var height = video.videoHeight;

        var squareSize = Math.min(width, height);

        canvas.width = squareSize;
        canvas.height = squareSize;

        var xOffset = (width - squareSize) / 2;
        var yOffset = (height - squareSize) / 2;

        context.drawImage(video, xOffset, yOffset, squareSize, squareSize, 0, 0, squareSize,
        squareSize);

        var imgData = canvas.toDataURL();
        var img = document.createElement('img');
        img.src = imgData;
        img.classList.add('img-fluid');
        img.classList.add('myImage');
        $('#photoPreview').show();
        $('#photoPreview').empty().append(img);
        $('#deleteBtn').show();
        });

        // Hapus foto
        $('#deleteBtn').click(function() {
            $('#photoPreview').hide();
            $('#deleteBtn').hide();
        });

        // =============== KAMERA END ==========================


        $(document).ready(function() {
            $('#harga').mask('000.000.000', {
                reverse: true
            });

            // Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            })

            $('#photoPreview').hide();

            $("#kode").focus();

            $('#image-container').hide();

            // PREWIVEW GAMBAR

            $(document).on('click', '#prosesbarang', function(event) {
                var kode = $("#kode").val();
                var nama = $("#nama").val();
                var satuanid = $('select[name="satuan"]').val();
                // var arrayminhar = [];
                var harga = $("#harga").val();
                var src = $(".myImage").attr("src");

                console.log(kode, nama, satuanid, harga, src);
                $.ajax({
                    url: '{{ route('barang-store-hp') }}',
                    method: 'POST',
                    data: {
                        kode: kode,
                        nama: nama,
                        satuanid: satuanid,
                        harga: harga,
                        src: src
                    },
                    success: function(response) {
                        console.log('Data berhasil dikirim:', response);
                        showToast(response.message, response.status);
                        $("#kode").val('');
                        $("#nama").val('');
                        $("#kode").focus();
                        $("#harga").val('');
                        $("#gambar-preview").attr("src", '');
                        $('#photoPreview').hide();
                        $('#photoPreview').empty();
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

    /* KAMERA START */

    .camera-container {
        position: relative;
        padding-bottom: 100%;
        /* Mengubah proporsi menjadi 1:1 */
        height: 0;
        overflow: hidden;
    }

    .camera-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-container {
        width: 100%;
        height: 0;
        padding-bottom: 100%;
        /* Mengubah proporsi menjadi 1:1 */
        position: relative;
        overflow: hidden;
    }

    .photo-container img {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: auto;
    }

    /* KAMERA END */
</style>
@endsection
