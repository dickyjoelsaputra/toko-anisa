@extends('layouts.main')

@section('title', 'Edit Barang')

<style>
</style>

@section('content')
<div class="card">
    <div class="card-body">
        <div class="toast-container">
        </div>
        <form>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label>Scan Barcode</label>
                        <input id="kode" value="{{ $barang->kode }}" name="kode" type="text"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input id="nama" value="{{ $barang->nama }}" name="nama" type="text"
                            class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Satuan Barang</label>
                        <select class="form-control select2" style="width: 100%;" name="satuan">
                            <option selected disabled></option>
                            @foreach ($satuans as $satuan)
                            <option @selected($barang->satuans->id == $satuan->id) value="{{ $satuan->id }}">{{
                                $satuan->nama }} ||
                                {{ $satuan->alias }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <div class="my-wrapper">
                            @foreach ($barang->hargas as $harga)
                            <div class="input-group mb-2 minhar">
                                <span class="input-group-addon">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Harga</span>
                                    </div>
                                </span>
                                <input value="{{ $harga->harga }}" name="harga[]" id="harga" type="text"
                                    class="form-control harga" placeholder="Harga" />
                                {{-- ICON --}}
                                <span class="input-group-addon">
                                    <div class="input-group-prepend" style="height: 100%">
                                        <span class="input-group-text icon" id="basic-addon1"><i
                                                class="fa fa-trash"></i>
                                        </span>
                                    </div>
                                </span>
                                {{-- ICON --}}
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex flex-column align-items-center">
                            <button type="button" id="tambahharga" class="btn btn-primary mt-3 harga-minal">Tambah
                                Harga</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
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
                            <div id="photoPreview" class="photo-container">
                                <img id="gambar-preview" src=""
                                    class="img-fluid myImage">
                            </div>
                            @if ($barang->gambar)
                            <div class="d-flex flex-column align-items-center">
                                <button type="button" id="deleteBtn" class="btn btn-danger mt-3 mx-auto">Hapus
                                    Foto</button>
                            </div>
                            @else
                            <div class="d-flex flex-column align-items-center">
                                <button type="button" id="deleteBtn" class="btn btn-danger mt-3 mx-auto"
                                    style="display: none;">Hapus
                                    Foto</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex flex-column align-items-center">
                <button type="button" id="proses" class="btn btn-success mt-3 mb-5">Edit Barang</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
            // =============== KAMERA START =======================
            // Akses kamera
            navigator.mediaDevices.getUserMedia({
                    video: true
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
                img.id = "gambar-preview";
                img.classList.add('img-fluid');
                img.classList.add('myImage');

                $('#photoPreview').empty().append(img);
                $('#deleteBtn').show();
            });

            // Hapus foto
            $('#deleteBtn').click(function() {
                $('#photoPreview').empty();
                $('#deleteBtn').hide();
            });

            // =============== KAMERA END ==========================

            // HARGA AWAL MASK
            $('.harga').mask('000.000.000', {
                reverse: true
            });

            // HARGA
            $('#tambahharga').click(function() {
                var inputGroup = $(`
                <div class="input-group mb-2 minhar">
                                <span class="input-group-addon">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Harga</span>
                                    </div>
                                </span>
                                <input type="text" class="form-control harga" placeholder="Harga" />
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


            $(document).on('click', '#proses', function(event) {
                var kode = $("#kode").val();
                var nama = $("#nama").val();
                var satuanid = $('select[name="satuan"]').val();
                var arrayminhar = [];
                var src = $("#gambar-preview").attr("src");

                $('.minhar').each(function() {
                    var harga = $(this).find('.harga').val();
                    var data = {
                        harga: harga
                    };
                    arrayminhar.push(data);
                });

                $.ajax({
                    url: '{{ route('barang-update', ['id' => $barang->id]) }}',
                    method: 'PUT',
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


            var imageUrl = "{{ asset('storage/' . $barang->gambar) }}";
            var image = new Image();
            image.src = imageUrl;
            image.onload = function() {
            console.log("Gambar tersedia");
            $('#gambar-preview').attr('src', imageUrl);
            };
            image.onerror = function() {
            console.log("Gambar tidak tersedia");
            var defaultImageUrl = "{{ $barang->gambar }}";
            $('#gambar-preview').attr('src', defaultImageUrl);
            };
            // END
        });
</script>
<style>
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

    .input-group .icon {
        background-color: red;
        color: white;
    }

    /* TOAST */

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
</style>
@endsection
