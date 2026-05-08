@extends('layouts.main-layouts')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">{{ $subtitle }}</p>
                </div>
                @php $breadcrumbs = $breadcrumbs ?? []; @endphp
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $i => $bc)
                                @php $isLast = $i === count($breadcrumbs) - 1; @endphp
                                @if ($isLast)
                                    <li class="breadcrumb-item active" aria-current="page">{{ $bc['label'] }}</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{ $bc['url'] ?? '#' }}">{{ $bc['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show mt-3" role="alert">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <section class="section">
        <div class="row">

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Scan QR (Simulasi RFID)</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-2">
                            <button type="button" id="btn-start" class="btn round btn-primary">
                                Start Scan
                            </button>
                            <button type="button" id="btn-stop" class="btn round btn-light ms-1" disabled>
                                Stop
                            </button>
                        </div>

                        <div id="reader" style="width: 100%; max-width: 360px;"></div>

                        <form id="scanForm" method="POST" action="{{ route('inbound.scan') }}" class="d-none">
                            @csrf
                            <input type="hidden" name="epc" id="epcInput">
                        </form>

                        <hr>

                        <form method="POST" action="{{ route('inbound.scan') }}" data-use-loader="true">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Input EPC (manual)</label>
                                <div class="d-flex gap-2">
                                    <input name="epc" class="form-control" placeholder="contoh: TAG-000001" required>
                                    <button class="btn btn-secondary round" type="submit">Tambah</button>
                                </div>
                            </div>
                        </form>

                        <small class="text-muted">
                            QR harus berisi teks yang sama dengan kolom <b>epc</b> pada RFID Tags.
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Draft Scan</h4>
                        <span class="badge bg-light-primary">Total: {{ $inbound_count ?? 0 }}</span>
                    </div>

                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <form method="POST" action="{{ route('inbound.draft.reset') }}" class="me-2">
                                @csrf
                                <button class="btn round btn-light" type="submit"
                                    {{ ($inbound_count ?? 0) == 0 ? 'disabled' : '' }}>
                                    Reset Draft
                                </button>
                            </form>

                            <form method="POST" action="{{ route('inbound.commit') }}" id="form-commit"
                                data-use-loader="true">
                                @csrf
                                <button class="btn round btn-success" type="button" id="btn-commit"
                                    {{ ($inbound_count ?? 0) == 0 ? 'disabled' : '' }}>
                                    Simpan Inbound
                                </button>
                                <button type="submit" id="real-commit" class="d-none"></button>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="tableDraft">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>EPC</th>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>State</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inbounds as $i => $t)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $t->epc }}</td>
                                            <td>{{ $t->product_sku }} - {{ $t->product_name }}</td>
                                            <td>
                                                @if ($t->status == 'Active')
                                                    <span class="badge bg-light-success">Active</span>
                                                @elseif ($t->status == 'Damaged')
                                                    <span class="badge bg-light-warning">Damaged</span>
                                                @else
                                                    <span class="badge bg-light-danger">Invalid</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($t->state == 'New')
                                                    <span class="badge bg-light-primary">New</span>
                                                @elseif ($t->state == 'In_Stock')
                                                    <span class="badge bg-light-info">In Stock</span>
                                                @else
                                                    <span class="badge bg-light-danger">Out</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('inbound.draft.remove') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $t->id }}">
                                                    <button class="btn btn-sm btn-danger round" type="submit">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada tag di draft</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <small class="text-muted">
                            Draft adalah daftar tag yang sudah valid. Klik <b>Simpan Inbound</b> untuk konfirmasi
                            penerimaan.
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        $(function() {
            let qr = null;
            let running = false;
            let locked = false;

            async function startScan() {
                if (running) return;
                locked = false;

                qr = new Html5Qrcode("reader");
                running = true;
                $('#btn-start').prop('disabled', true);
                $('#btn-stop').prop('disabled', false);

                try {
                    await qr.start({
                            facingMode: "environment"
                        }, {
                            fps: 10,
                            qrbox: 250
                        },
                        async (decodedText) => {
                            if (locked) return;
                            locked = true;

                            try {
                                await qr.stop();
                            } catch (e) {}

                            running = false;
                            $('#btn-start').prop('disabled', false);
                            $('#btn-stop').prop('disabled', true);

                            $('#epcInput').val(decodedText);
                            $('#scanForm').submit();
                        }
                    );
                } catch (e) {
                    running = false;
                    $('#btn-start').prop('disabled', false);
                    $('#btn-stop').prop('disabled', true);

                    Swal.fire({
                        icon: 'error',
                        title: 'Camera error',
                        text: 'Tidak bisa akses kamera. Pastikan izin kamera aktif.',
                    });
                }
            }

            async function stopScan() {
                if (!qr || !running) return;
                try {
                    await qr.stop();
                } catch (e) {}
                running = false;
                $('#btn-start').prop('disabled', false);
                $('#btn-stop').prop('disabled', true);
            }

            $('#btn-start').on('click', startScan);
            $('#btn-stop').on('click', stopScan);

            $('#btn-commit').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Simpan Inbound?',
                    text: 'Draft akan disimpan dan stok akan bertambah.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754',
                }).then((res) => {
                    if (!res.isConfirmed) return;
                    $('#real-commit').click();
                });
            });
        });
    </script>
@endpush
