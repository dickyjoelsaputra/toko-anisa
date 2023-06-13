@extends('layouts.main')

@section('title', 'Transaksi')

@section('content')

<!-- Default box -->
<div class="card">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waktu Transaksi</th>
                    <th>User</th>
                    <th>Total Transaksi</th>
                    <th>Uang Pembeli</th>
                    <th>Kembalian</th>
                    {{-- <th style="width: 20%;">Action</th> --}}
                </tr>
            </thead>

            <div class="form-inline">
                <div class="mx-2">
                    {{ $transaksis->withQueryString()->links() }}
                </div>
                <form action="" method="GET" id="search-form">
                    {{-- <input class="" name="date" id="datepicker" width="276" /> --}}
                    {{-- {{$date}} --}}
                    <input class="form-control" value="{{ isset($date) ? $date : '' }}" type="date" id="date"
                        name="date">
                    <button type="submit" id="submit" class="btn btn-primary mx-2">Submit</button>
                </form>
            </div>

            <tbody>
                @foreach ($transaksis as $transaksi)
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->created_at }}</td>
                    <td>{{ $transaksi->users->nama }}</td>
                    <td class="transaksitotal">{{ $transaksi->total }}</td>
                    <td class="uangpembeli">{{ $transaksi->uangpembeli }}</td>
                    <td class="kembalian">{{ $transaksi->kembalian }}</td>
                </tr>
                <tr class="expandable-body d-none">
                    <td colspan="6">
                        <p>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Harga Barang</th>
                                    <th>Jumlah / Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksi->keranjangs as $keranjang)
                                <tr>
                                    <td>{{ $keranjang->barangs->nama }}</td>
                                    <td>{{ $keranjang->hargas->harga }}</td>
                                    <td>{{ $keranjang->jumlah }}</td>
                                    <td>{{ $keranjang->keranjang_harga }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // give mask to $transaksi total , uangpembeli, kembalian and also $keranjang hargas harga , keranjang_harga
    $(document).ready(function () {
        $('.transaksitotal , .uangpembeli,.kembalian').mask('000.000.000', { reverse: true });
    });

</script>

<style>
    .form-inline {
        display: flex;
        flex-flow: row wrap;
        align-items: flex-start;
        margin-bottom: 10px;
    }
</style>

@endsection
