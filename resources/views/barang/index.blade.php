@extends('layouts.main')

@section('title', 'Index Barang')

<style>
</style>

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered mb-3" id="barang-table" style="width: 100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nama</th>
                    <th>Kode</th>
                    <th>Manual</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Satuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>

    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
            $('#barang-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                ajax: "{{ route('barang-ajax-index') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
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
                        name: 'manual',
                        render: function(data) {
                            if (data === 0) {
                                return 'Tidak';
                            } else if (data === 1) {
                                return 'Ya';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'gambar',
                        name: 'gambar',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, full, meta) {
                            if (type === 'display') {
                                    var imageSrc = '{{ asset('storage/') }}' + '/' + data;
                                    var http = new XMLHttpRequest();
                                    http.open('HEAD', imageSrc, false);
                                    http.send();

                                    if (http.status === 200) {
                                    // Gunakan gambar dari asset jika tersedia
                                    return '<img src="' + imageSrc + '" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;">';
                                    } else {
                                    // Gunakan gambar fallback jika tidak tersedia di asset
                                    return '<img src="' + data + '" alt="" class="img-thumbnail" style="object-fit: contain; width: 100px; height: 100px;">';
                                    }
                            }
                            return data;
                        }
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        orderable: false,
                        searchable: false,

                    },
                    {
                        data: 'satuans.alias',
                        name: 'satuans.alias'
                    },
                    {
                        data: null,
                        searchable: false,
                        orderable: false,
                        render: function(data, type, full, meta) {
                            var editUrl = "{{ route('barang-edit', ':id') }}".replace(':id', data
                                .id);
                            var deleteUrl = "{{ route('barang-destroy', ':id') }}".replace(':id',
                                data.id);
                            var buttons = '<div class="d-flex flex-column">';
                            buttons += '<a href="' + editUrl +
                                '" class="btn btn-primary mb-2"><i class="fa fa-edit"></i> Edit</a>';
                            buttons += '<button class="btn btn-danger mb-2 w-100 delete-btn" data-url="' + deleteUrl + '"><i class="fa fa-trash"></i> Delete</button>';
                            buttons += '</div>';
                            return buttons;
                        }
                    },

                ]
            });

            $('#barang-table').on('draw.dt', function() {
                $('.harga-tbl').each(function() {
                    $(this).mask('000.000.000', {
                        reverse: true
                    });
                });
            });


            // AJAX DELETE
            $('#barang-table').on('click', '.delete-btn', function() {
                        var deleteUrl = $(this).data('url');

                        if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                        $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}'
                        },
                        success: function(result) {
                        // Refresh data pada tabel setelah berhasil menghapus
                      $('#barang-table').DataTable().ajax.reload();
                        console.log(result);
                        },
                        error: function(xhr) {
                        // Tampilkan pesan error jika terjadi kesalahan
                        console.log(xhr.responseText);
                        }
                        });
                        }
                    });
        });
</script>
@endsection
