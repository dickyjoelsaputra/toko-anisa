@extends('layouts.main')

@section('title', 'Transaksi')

@section('content')
<!-- Default box -->
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="form-group">
                <label>Start Date:</label>
                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate">
                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>End Date:</label>
                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate">
                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary">Primary</button>
        </div>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Waktu Transaksi</th>
                    <th>User</th>
                    <th>Total Transaksi</th>
                    {{-- <th style="width: 20%;">Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksis as $transaksi)
                <tr data-widget="expandable-table" aria-expanded="false">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->created_at }}</td>
                    <td>{{ $transaksi->users->nama }}</td>
                    <td>{{ $transaksi->total }}</td>
                </tr>
                <tr class="expandable-body d-none">
                    <td colspan="4">
                        <p>
                        <table class="table table-dark table-bordered">
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
<script></script>
@endsection
