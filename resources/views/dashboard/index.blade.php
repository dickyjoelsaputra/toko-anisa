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
<div class="card mb-5">
    <canvas id="chart">
    </canvas>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{ route('dashboard-ajaxchart') }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response)
                var labels = response.map(function(item) {
                    return item.month;
                });

                var data = response.map(function(item) {
                    return item.total;
                });

                var ctx = document.getElementById('chart');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Transaksi',
                            data: data,
                            backgroundColor: 'rgba(54, 162, 235)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                            return value.toLocaleString();
                            }
                            }
                            },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
      $('#labakotor').mask('000.000.000', {
        reverse: true
        });
    });
</script>
@endsection
