@extends('layouts.main')

@section('title', 'Barang')

@section('content')

    <form id="scannerForm" method="POST">
        @csrf
        <input type="text" name="scanner" id="scannerInput">
        <button type="submit">Simpan</button>
    </form>

    <ol id="scannerDataList"></ol>
@endsection

@section('scripts')
    <script></script>

@endsection
