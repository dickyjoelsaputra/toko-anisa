@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Jumlah Transaksi</span>
                <span class="info-box-number">{{$jumlahtransaksi}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fa fa-paper-plane" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Laba Kotor</span>
                <span class="info-box-number" id="labakotor">{{$labakotor}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-4 col-sm-4 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fa fa-folder-open" aria-hidden="true"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Jumlah Barang</span>
                <span class="info-box-number">{{$jumlahbarang}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<div class="chart">
    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
</div>
@endsection

@section('scripts')
<script>
    //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
        responsive : true,
        maintainAspectRatio : false,
        datasetFill : false
        }

        new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
        })


    $(document).ready(function() {
        // format mask laba kotor
        $('#labakotor').mask('000.000.000', {
            reverse: true
        });

    });
</script>
@endsection
