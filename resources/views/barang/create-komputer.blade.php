@extends('layouts.main')

@section('title', 'Tambah Barang Via Komputer')

<style>
</style>

@section('content')
    <div class="card">
        <div class="card-body">
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
                            <label>Harga dan minimal</label>
                            <div class="my-wrapper">
                                <div class="input-group mb-2 minhar">
                                    <span class="input-group-addon">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">Minimal</span>
                                        </div>
                                    </span>
                                    <input name="minimal[]" id="minimal" value="1" type="text"
                                        class="form-control minimal" placeholder="Minimal Pembelian" />
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
                                    Minimal &
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

            $("#kode").focus();

            // Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            })
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



            // UNTUK TAMBAH DAN HARGA
            $('#tambahharga').click(function() {
                var inputGroup = $(`
                <div class="input-group mb-2 minhar">
                                <span class="input-group-addon">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">Minimal</span>
                                    </div>
                                </span>
                                <input type="text" class="form-control minimal" placeholder="Minimal Pembelian" />
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
                    var minimal = $(this).find('.minimal').val();
                    var harga = $(this).find('.harga').val();
                    var data = {
                        minimal: minimal,
                        harga: harga
                    };
                    arrayminhar.push(data);
                });

                var src = $(".myImage").attr("src"); // Mendapatkan nilai atribut 'src'
                var imageData = src.split(",")[
                    1]; // Mendapatkan data base64 (tanpa "data:image/png;base64,")
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
                                            <th>Minimal</th>
                                            <th>Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${arrayminhar.map(item => `
                                                                <tr>
                                                                <td>${item.minimal}</td>
                                                                <td>${item.harga}</td>
                                                                </tr>
                                                                `).join('')}
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
                $('.minimal').val('');
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
                event.preventDefault();
                $.ajax({
                    url: '{{ route('barang-store-komputer') }}',
                    method: 'POST',
                    data: finaldata,
                    success: function(response) {
                        console.log('Data berhasil dikirim:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error dalam mengirim data:', error);
                    }
                });
            });

        });
    </script>
    <style>
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
