@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    {{-- <h3>{{ $title }}</h3> --}}
                    {{-- <p class="text-subtitle text-muted">{{ $subtitle }}</p> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Products</div>
                            {{-- <div class="h4 mb-0">{{ number_format($totalProducts) }}</div> --}}
                        </div>
                        <i class="bi bi-box-seam fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Total Stock</div>
                            {{-- <div class="h4 mb-0">{{ number_format($totalStock) }}</div> --}}
                        </div>
                        <i class="bi bi-archive fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">Open Orders</div>
                            {{-- <div class="h4 mb-0">{{ number_format($openOrders) }}</div> --}}
                        </div>
                        <i class="bi bi-receipt fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted">RFID In Stock</div>
                            {{-- <div class="h4 mb-0">{{ number_format($tagsInStock) }}</div> --}}
                        </div>
                        <i class="bi bi-qr-code-scan fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Inbound vs Outbound (14 Hari Terakhir)</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartMovement" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Sales Orders by Status</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartStatus" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Top 10 Products by Stock</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartTopStock" height="110"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script>
        (function() {
            const mvLabels = @json($mvLabels);
            const mvIn = @json($mvInbound);
            const mvOut = @json($mvOutbound);

            new Chart(document.getElementById('chartMovement'), {
                type: 'line',
                data: {
                    labels: mvLabels,
                    datasets: [{
                            label: 'Inbound',
                            data: mvIn,
                            tension: 0.3
                        },
                        {
                            label: 'Outbound',
                            data: mvOut,
                            tension: 0.3
                        },
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const stLabels = @json($statusLabels);
            const stTotals = @json($statusTotals);

            new Chart(document.getElementById('chartStatus'), {
                type: 'doughnut',
                data: {
                    labels: stLabels,
                    datasets: [{
                        data: stTotals
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            const topLabels = @json($topLabels);
            const topQty = @json($topQty);

            new Chart(document.getElementById('chartTopStock'), {
                type: 'bar',
                data: {
                    labels: topLabels,
                    datasets: [{
                        label: 'Qty',
                        data: topQty
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })();
    </script> --}}
@endpush
