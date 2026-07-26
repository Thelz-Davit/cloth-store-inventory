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
                    <h4 class="mb-0">Ringkasan Inventaris</h4>
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
                                    <h4 class="fw-bold mb-1">{{ $todayInbound }}</h4>
                                    <small class="text-muted fs-6">Barang Masuk</small>
                                </div>

                                <div>
                                    <h4 class="fw-bold mb-1">{{ $todayOutbound }}</h4>
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
                    <h4 class="card-title mb-0">Tren Harian</h4>
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
                <div class="card-body p-0">

                    @forelse($lowStockItems as $item)
                        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-bottom">

                            <div>
                                <h6 class="mb-1 fw-semibold">
                                    {{ $item->product_name }}

                                    @if($item->color)
                                        - {{ $item->color->color_name }}
                                    @endif

                                    @if($item->size)
                                        / {{ $item->size->size_name }}
                                    @endif
                                </h6>

                                <small class="text-muted">
                                    Remaining Quantity : {{ $item->stock }} Item
                                </small>
                            </div>

                            <span class="badge rounded-pill text-danger bg-danger-subtle px-3 py-2">
                                Low
                            </span>

                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle text-success fs-1"></i>
                            <p class="text-muted mt-2 mb-0">
                                No low stock products.
                            </p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Transaksi Terbaru</h4>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Products</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($activity->date)->format('d M Y') }}</td>
                                    <td>
                                        @if($activity->type === 'Inbound')
                                            <span class="badge bg-success">Inbound</span>
                                        @else
                                            <span class="badge bg-danger">Outbound</span>
                                        @endif
                                    </td>
                                    <td>{{ $activity->product->product_name ?? $activity->bundle->bundle_name }}</td>
                                    <td>{{ $activity->quantity }}</td>
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
                        data: @json($inboundData)
                    },
                    {
                        name: "Outbound",
                        data: @json($outboundData)
                    }
                ],

                chart: {
                    type: 'area',
                    height: 350
                },

                stroke: {
                    curve: 'smooth'
                },

                xaxis: {
                    categories: @json($days)
                }
            }

                

            new ApexCharts(
                document.querySelector("#inventoryChart"),
                barOptions
            ).render();
    </script>
@endpush
