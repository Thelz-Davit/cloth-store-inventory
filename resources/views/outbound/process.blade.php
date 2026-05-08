@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">{{ $subtitle }}</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show mt-3">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Scan QR (Kamera)</h4>
                </div>
                <div class="card-body">

                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn-primary" id="btnStart">Start</button>
                        <button type="button" class="btn btn-light" id="btnStop" disabled>Stop</button>
                    </div>

                    <div id="reader" style="width: 100%; max-width: 380px;"></div>

                    <form id="scanForm" method="POST" action="{{ route('outbound.scan', $order->id) }}" class="d-none">
                        @csrf
                        <input type="hidden" name="epc" id="epcHidden">
                    </form>

                    <hr>

                    <form method="POST" action="{{ route('outbound.scan', $order->id) }}" data-use-loader="true">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Input EPC manual</label>
                            <div class="d-flex gap-2">
                                <input name="epc" class="form-control" placeholder="contoh: TAG-000001" required>
                                <button class="btn btn-secondary" type="submit">Cek</button>
                            </div>
                            <small class="text-muted">Scan kamera akan mengisi EPC otomatis dan submit ke server.</small>
                        </div>
                    </form>

                    <hr>
                    <div>
                        <b>Order:</b> {{ $order->order_no }}<br>
                        <b>Customer:</b> {{ $order->customer_name ?? '-' }}<br>
                        <b>Status:</b> {{ $order->status ?? 'Open' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Progress Order</h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product</th>
                                    <th>Order</th>
                                    <th>Scanned</th>
                                    <th>Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $i => $it)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $it->sku }} - {{ $it->product_name }}</td>
                                        <td><span class="badge bg-light-primary">{{ (int) $it->qty_order }}</span></td>
                                        <td><span class="badge bg-light-info">{{ (int) $it->qty_scanned }}</span></td>
                                        <td>
                                            @if ((int) $it->remaining == 0)
                                                <span class="badge bg-light-success">OK</span>
                                            @else
                                                <span class="badge bg-light-warning">{{ (int) $it->remaining }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h6 class="mb-2">Draft Scan</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>EPC</th>
                                    <th>Product</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($draftTags as $i => $d)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $d->epc }}</td>
                                        <td>{{ $d->sku }} - {{ $d->product_name }}</td>
                                        <td><span class="badge bg-light-primary">{{ (int) $d->qty }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Belum ada scan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('outbound.index') }}" class="btn btn-light">Back</a>

                        <form method="POST" action="{{ route('outbound.commit', $order->id) }}" id="formCommit"
                            data-use-loader="true">
                            @csrf
                            <button type="button" class="btn btn-success" id="btnCommit"
                                {{ !$canCommit ? 'disabled' : '' }}>
                                Commit Outbound
                            </button>
                            <button type="submit" id="realCommit" class="d-none"></button>
                        </form>

                        @if (!$canCommit)
                            <small class="text-muted align-self-center">*Commit aktif jika semua remaining sudah OK.</small>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        $(function() {
            let scanner = null;
            let running = false;
            let lock = false;

            async function start() {
                if (running) return;
                scanner = new Html5Qrcode("reader");
                running = true;
                lock = false;

                $('#btnStart').prop('disabled', true);
                $('#btnStop').prop('disabled', false);

                try {
                    await scanner.start({
                            facingMode: "environment"
                        }, {
                            fps: 10,
                            qrbox: 250
                        },
                        async (text) => {
                            if (lock) return;
                            lock = true;

                            try {
                                await scanner.stop();
                            } catch (e) {}
                            running = false;

                            $('#btnStart').prop('disabled', false);
                            $('#btnStop').prop('disabled', true);

                            $('#epcHidden').val((text || '').trim());
                            $('#scanForm').submit();
                        }
                    );
                } catch (e) {
                    running = false;
                    $('#btnStart').prop('disabled', false);
                    $('#btnStop').prop('disabled', true);

                    Swal.fire({
                        icon: 'error',
                        title: 'Camera error',
                        text: 'Tidak bisa akses kamera. Pastikan izin kamera aktif & gunakan HTTPS.',
                    });
                }
            }

            async function stop() {
                if (!scanner || !running) return;
                try {
                    await scanner.stop();
                } catch (e) {}
                running = false;

                $('#btnStart').prop('disabled', false);
                $('#btnStop').prop('disabled', true);
            }

            $('#btnStart').on('click', start);
            $('#btnStop').on('click', stop);

            $('#btnCommit').on('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Commit Outbound?',
                    text: 'Stok akan berkurang dan status order jadi Done.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, commit',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754',
                }).then((res) => {
                    if (!res.isConfirmed) return;
                    $('#realCommit').click();
                });
            });
        });
    </script>
@endpush
