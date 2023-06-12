@extends('layouts.main')

@section('title', 'Tambah Barang Via Komputer')

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
                                <input name="harga[]" id="harga" type="text" class="form-control harga"
                                    placeholder="Harga" />
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
                            <div id="photoPreview" class="photo-container"></div>
                            <div class="d-flex flex-column align-items-center">
                                <button type="button" id="deleteBtn" class="btn btn-danger mt-3 mx-auto"
                                    style="display: none;">Hapus
                                    Foto</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex flex-column align-items-center">
                <button type="button" id="tambah" class="btn btn-success mt-3">Tambah Barang</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h4>Result</h4>
        <div class="row row-cols-4 okcard gutter">
        </div>

        <hr>
        <div class="d-flex flex-column align-items-center">
            <button type="button" id="proses" class="btn btn-success mt-3 mb-5">Proses Barang</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

            $('.harga').mask('000.000.000', {
                reverse: true
            });
            $("#kode").focus();

            // Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            })
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

            // UNTUK TAMBAH DAN HARGA
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

            $(document).on('click', '#tambah', function(event) {
                var kode = $("#kode").val();
                var nama = $("#nama").val();
                var satuanid = $('select[name="satuan"]').val();
                var satuanvalue = $('select[name="satuan"] option:selected').text();
                var arrayminhar = [];

                $('.minhar').each(function() {
                    var harga = $(this).find('.harga').val();
                    var data = {
                        harga: harga
                    };
                    arrayminhar.push(data);
                });

                var src = $(".myImage").attr("src"); // Mendapatkan nilai atribut 'src'
                console.log(src)
                if (src == undefined) {
                src = "https://static.vecteezy.com/system/resources/previews/005/337/799/original/icon-image-not-found-free-vector.jpg";
                }
                // var imageData = src.split(",")[
                    // 1]; // Mendapatkan data base64 (tanpa "data:image/png;base64,")
                var fileName = "image.png"; // Nama file yang diinginkan

                card = `
                <div class="col">
                    <div class="card border border-secondary">
                        <img src="${src}" class="card-img-top" alt="${fileName}">
                            <div class="cross-icon">
                                <i class="fas fa-times"></i>
                            </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <h5 class="card-title"><b>Nama : </b>${nama}</h5>
                                </li>
                            <li class="list-group-item">
                                <h5 class="card-title"><b>Kode : </b>${kode}</h5>
                                </li>
                            <li class="list-group-item">
                                <h5 class="card-title"><b>Satuan : </b>${satuanvalue}</h5>
                            </li>
                            <li class="list-group-item">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${arrayminhar.map(item => `<tr><td>${item.harga}</td></tr>`).join('')}
                                    </tbody>
                                </table>
                            </li>
                        </ul>
                    </div>
                </div>
                 `;

                var objectData = {
                    kode: kode,
                    nama: nama,
                    satuanid: satuanid,
                    satuanvalue: satuanvalue,
                    minhar: arrayminhar,
                    src: src,
                    filename: fileName
                }

                finaldata.push(objectData)

                // console.log(finaldata)

                var cardcontainer = $(".okcard")
                cardcontainer.prepend(card);

                $("#kode").val('');
                $("#nama").val('');
                $('select[name="satuan"]').val('').trigger('change');
                $('.harga').val('');
                $('.minhar:not(:first)').remove();
                $('#photoPreview').empty();
                $('#deleteBtn').hide();
            });

            // HAPUS BAWAH
            $(document).on('click', '.cross-icon', function() {
                var $col = $(this).closest('.col');
                $col.remove();

                var src = $col.find('.card-img-top').attr('src');
                finaldata = finaldata.filter(function(item) {
                    return item.src !== src;
                });

                // console.log(finaldata);
            });

            var finaldata = []
            var card;

            $(document).on('click', '#proses', function(event) {
                console.log("output", finaldata);
                event.preventDefault();
                $.ajax({
                    url: '{{ route('barang-store-komputer') }}',
                    method: 'POST',
                    data: {
                        finalData: finaldata
                    },
                    success: function(response) {
                        console.log('Data berhasil dikirim:', response);
                        showToast(response.message, response.status);
                        $('.row-cols-4.okcard .col').each(function() {
                            $(this).empty(); // Menghapus konten dalam col
                            $(this).remove(); // Menghapus col itu sendiri
                        });
                        $("#kode").focus();
                        // clear final data
                        finaldata = [];
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
        });
</script>
<style>
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


    .input-group-addon {
        border-left-width: 0;
        border-right-width: 0;
    }

    .input-group-addon:first-child {
        border-left-width: 1px;
    }

    .input-group-addon:last-child {
        border-right-width: 1px;
    }

    .input-group .icon {
        background-color: red;
        color: white;
    }

    .okcard .col {
        padding: 10px;
    }

    .cross-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background-color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }


</style>
@endsection
