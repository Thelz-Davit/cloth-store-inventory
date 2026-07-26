@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Dashboard</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ringkasan Inventaris</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 border-end">
                            <h6 class="text-primary fw-bold mb-3">
                                <i class="bi bi-box-seam me-1"></i>
                                Total Produk
                            </h6>

                            <h2 class="fw-bold mb-1">{{ $totalProducts }}</h2>

                            <small class="text-muted">
                                Produk Terdaftar
                            </small>
                        </div>

                        <div class="col-md-3 border-end">
                            <h6 class="text-warning fw-bold mb-3">
                                <i class="bi bi-boxes me-1"></i>
                                Total Bundling
                            </h6>

                            <h2 class="fw-bold mb-1">{{ $totalBundles }}</h2>

                            <small class="text-muted">
                                Bundle Aktif
                            </small>
                        </div>

                        <div class="col-md-3 border-end">
                            <h6 class="text-success fw-bold mb-3">
                                <i class="bi bi-arrow-left-right me-1"></i>
                                Transaksi Hari Ini
                            </h6>

                            <div class="d-flex justify-content-around">
                                <div>
                                    <h4 class="fw-bold mb-1">{{ $inboundTopCard }}</h4>
                                    <small class="text-muted fs-6">Barang Masuk</small>
                                </div>

                                <div>
                                    <h4 class="fw-bold mb-1">{{ $outboundTopCard }}</h4>
                                    <small class="text-muted fs-6">Barang Keluar</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <h6 class="fw-bold mb-3 {{ $lowStock > 0 ? 'text-danger' : 'text-success' }}">
                                <i
                                    class="bi {{ $lowStock > 0 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }} me-1 fs-5"></i>
                                Kesehatan Persediaan
                            </h6>

                            <h2 class="fw-bold mb-1 {{ $lowStock > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $lowStock }}
                            </h2>

                            <small class="text-muted">
                                {{ $lowStock > 0 ? 'Perlu Stok Ulang' : 'Stok Aman' }}
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Tren Bulanan</h4>
                </div>
                <div class="card-body">
                    <div id="inventoryChart" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Status Persediaan</h4>
                </div>
                <div class="card-body">
                    <div id="chartStock" style="height: 100%"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Bundle Paling Laris</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Bundle</th>
                                <th>Total Outbound</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topBundles as $bundle)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bundle->bundle->bundle_name }}</td>
                                    <td>{{ $bundle->total_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        var barOptions = {
                series: [
                    {
                        name: "Inbound",
                        data: @json($inboundSeries)
                    },
                    {
                        name: "Outbound",
                        data: @json($outboundSeries)
                    }
                ],

                chart: {
                    type: 'bar',
                    height: 350
                },

                stroke: {
                    curve: 'smooth'
                },

                xaxis: {
                    categories: @json($months)
                }
            }

                var options = {
                        series: @json([$lowStock, $inStock]),
                        chart: {
                            type: 'donut',
                        },
                        labels: [
                            'In Stock',
                            'Low Stock',
                        ],
                        legend: {
                            show: true,
                        },
                        plotOptions: {
                            pie: {
                                dataLabels: {
                                    external: {
                                        show: true,
                                    },
                                },
                            },
                        },
                        responsive: [
                            {
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 320,
                                    },
                                },
                            },
                        ],
                    }

                    var chart = new ApexCharts(document.querySelector('#chartStock'), options)
                    chart.render()

            new ApexCharts(
                document.querySelector("#inventoryChart"),
                barOptions
            ).render();
    </script>
@endpush
