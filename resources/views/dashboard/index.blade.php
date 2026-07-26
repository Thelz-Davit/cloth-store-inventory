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
            {{-- <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ringkasan Inventaris</h4>
                </div>
                <div class="card-body">
                    <div class="row lg-3">
                        <!-- Total Product -->
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Produk</h6>
                                    <h2 class="mb-0">{{ $totalProducts }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Total Sold -->
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Total Bundling</h6>
                                    <h2 class="mb-0">{{$totalBundles }}</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Optional: Stock -->
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="text-muted text-center">Mutasi Harian</h6>

                                    <div class="d-flex justify-content-between">
                                        <div class="text-center">
                                            <h2 class="mb-0">{{ $todayInbound }}</h2>
                                            <small>Barang Masuk</small>
                                        </div>

                                        <div class="text-center">
                                            <h2 class="mb-0">{{ $todayOutbound }}</h2>
                                            <small>Barang Keluar</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Optional: Revenue -->
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Kesehatan Persediaan</h6>
                                    <h2 class="mb-0">
                                    {{$lowStock}}</h2>
                                    <sub>Perlu Stock</sub>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-grid-1x2-fill me-2"></i>
                        Ringkasan Inventaris
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row g-4">

                        <!-- Total Products -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card h-100 bg-primary bg-opacity-10 border-0 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">

                                    <div>
                                        <p class="text-muted mb-1">Total Produk</p>
                                        <h2 class="fw-bold mb-0">{{ $totalProducts }}</h2>
                                    </div>

                                    <div class="bg-primary rounded-circle p-3">
                                        <i class="bi bi-box-seam text-white fs-3"></i>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Total Bundles -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card h-100 bg-success bg-opacity-10 border-0 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">

                                    <div>
                                        <p class="text-muted mb-1">Total Bundling</p>
                                        <h2 class="fw-bold mb-0">{{ $totalBundles }}</h2>
                                    </div>

                                    <div class="bg-success rounded-circle p-3">
                                        <i class="bi bi-boxes text-white fs-3"></i>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Daily Movement -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card h-100 bg-warning bg-opacity-10 border-0 shadow-sm">
                                <div class="card-body">

                                    <p class="text-muted mb-3 text-center">
                                        Mutasi Hari Ini
                                    </p>

                                    <div class="d-flex justify-content-around align-items-center">

                                        <div class="text-center">
                                            <i class="bi bi-arrow-down-circle-fill text-success fs-2"></i>
                                            <h4 class="fw-bold mt-2 mb-0">
                                                {{ $todayInbound }}
                                            </h4>
                                            <small class="text-muted">
                                                Barang Masuk
                                            </small>
                                        </div>

                                        <div class="vr"></div>

                                        <div class="text-center">
                                            <i class="bi bi-arrow-up-circle-fill text-danger fs-2"></i>
                                            <h4 class="fw-bold mt-2 mb-0">
                                                {{ $todayOutbound }}
                                            </h4>
                                            <small class="text-muted">
                                                Barang Keluar
                                            </small>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Inventory Health -->
                        <div class="col-md-6 col-xl-3">
                            <div
                                class="card h-100 {{ $lowStock > 0 ? 'bg-danger bg-opacity-10' : 'bg-success bg-opacity-10' }} border-0 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">

                                    <div>
                                        <p class="text-muted mb-1">
                                            Kesehatan Persediaan
                                        </p>

                                        <h2 class="fw-bold mb-0 {{ $lowStock > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $lowStock }}
                                        </h2>

                                        <small class="text-muted">
                                            {{ $lowStock > 0 ? 'Produk Perlu Restock' : 'Semua Produk Aman' }}
                                        </small>
                                    </div>

                                    <div class="{{ $lowStock > 0 ? 'bg-danger' : 'bg-success' }} rounded-circle p-3">
                                        <i
                                            class="bi {{ $lowStock > 0 ? 'bi-exclamation-triangle-fill' : 'bi-check-circle-fill' }} text-white fs-3"></i>
                                    </div>

                                </div>
                            </div>
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
                    <h4 class="card-title mb-0">Stok Menipis</h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStock as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                            @foreach($lowStockItems as $itemLow)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $itemLow->product->product_name }} <sub>{{ $itemLow->stock }}</sub></td>
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
