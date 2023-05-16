@extends('layouts.main')

@section('title', 'Barang')

@section('content')
    <div class="card">
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-default">
                Tambah Barang
            </button>
            <table class="table table-bordered" id="barang-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Manual</th>
                        <th>Gambar</th>
                        {{-- <th>Satuan</th> --}}
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(function() {
                $('#barang-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('barang-ajax-index') }}", // memanggil route yang menampilkan data json
                    columns: [{ // mengambil & menampilkan kolom sesuai tabel database
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'kode',
                            name: 'kode'
                        },
                        {
                            data: 'manual',
                            name: 'manual'
                        },
                        {
                            data: 'gambar',
                            name: 'gambar'
                        }
                    ]
                });
            });
        });
    </script>
@endsection
