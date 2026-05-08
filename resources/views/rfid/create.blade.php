@extends('layouts.main-layouts')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title ?? 'Create RFID Tag' }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ $subtitle ?? 'Tambah RFID/QR Tag untuk produk' }}
                    </p>
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
                                    <li class="breadcrumb-item">
                                        <a href="{{ $bc['url'] ?? '#' }}">{{ $bc['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section mt-3">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title">Form RFID / QR Tag</h4>
                </div> --}}
                @php
                    $isEdit = isset($rfid) && $rfid;
                @endphp

                <div class="card-body">
                    <form action="{{ $isEdit ? route('rfid-tags.update', $rfid->id) : route('rfid-tags.store') }}"
                        data-use-loader="true" method="POST">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="epc">EPC / QR Value <span class="text-danger">*</span></label>
                                    <input type="text" id="epc" name="epc"
                                        class="form-control @error('epc') is-invalid @enderror"
                                        value="{{ old('epc', $isEdit ? $rfid->epc : '') }}"
                                        placeholder="Contoh: TAG-000001 / E200..." required>
                                    @error('epc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Isi ini adalah kode unik yang nanti akan discan pakai HP (QR).
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Product <span class="text-danger">*</span></label>
                                    <select id="product_id" name="product_id"
                                        class="form-select @error('product_id') is-invalid @enderror" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach ($products as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('product_id', $isEdit ? $rfid->product_id : '') == $p->id ? 'selected' : '' }}>
                                                {{ $p->sku }} - {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    @php
                                        $statuses = [
                                            'Active' => 'Active',
                                            'Inactive' => 'Inactive',
                                            'Damaged' => 'Damaged',
                                        ];
                                        $oldStatus = old('status', $isEdit ? $rfid->status : 'Active');
                                    @endphp

                                    <select name="status" class="form-select @error('status') is-invalid @enderror"
                                        required>
                                        @foreach ($statuses as $k => $v)
                                            <option value="{{ $k }}" {{ $oldStatus == $k ? 'selected' : '' }}>
                                                {{ $v }}</option>
                                        @endforeach
                                    </select>

                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state">State <span class="text-danger">*</span></label>
                                    @php
                                        $states = [
                                            'New' => 'New',
                                            'In_Stock' => 'In Stock',
                                            'Out' => 'Out',
                                        ];
                                        $oldState = old('state', $isEdit ? $rfid->state : 'New');
                                    @endphp

                                    <select name="state" class="form-select @error('state') is-invalid @enderror"
                                        required>
                                        @foreach ($states as $k => $v)
                                            <option value="{{ $k }}" {{ $oldState == $k ? 'selected' : '' }}>
                                                {{ $v }}</option>
                                        @endforeach
                                    </select>

                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        Default: <b>NEW</b>. Setelah inbound sukses → sistem ubah ke <b>IN_STOCK</b>.
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>QR Preview</label>
                                    <div class="border rounded p-3">
                                        <div id="qrPreview" class="d-flex justify-content-center"></div>

                                        <div class="mt-2 d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                id="btn-generate-qr">
                                                Generate QR
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="btn-print-qr" disabled>
                                                Print
                                            </button>
                                        </div>

                                        <small class="text-muted d-block mt-2">
                                            QR ini berisi nilai <b>EPC</b> dan itu yang akan discan saat Inbound.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="qty">Qty (Isi paket) <span class="text-danger">*</span></label>
                                    <input type="number" min="1" id="qty" name="qty"
                                        class="form-control @error('qty') is-invalid @enderror"
                                        value="{{ old('qty', $isEdit ? $rfid->qty : 1) }}" required>
                                    @error('qty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <a href="{{ route('rfid-tags.index') }}" class="btn btn-light">
                                Cancel
                            </a>
                            <button class="btn btn-primary" type="submit">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $(function() {

            function getEpc() {
                return ($('#epc').val() || '').trim();
            }

            function generateQr(epc) {
                const box = document.getElementById('qrPreview');
                box.innerHTML = '';

                new QRCode(box, {
                    text: epc,
                    width: 220,
                    height: 220
                });
                $('#btn-print-qr').prop('disabled', false);
            }

            function getQrDataUrl() {
                const box = document.getElementById('qrPreview');
                const canvas = box.querySelector('canvas');
                const img = box.querySelector('img');

                if (canvas) return canvas.toDataURL('image/png');
                if (img) return img.src;

                return null;
            }

            function downloadLabel(epc, qrDataUrl) {
                const c = document.createElement('canvas');
                c.width = 400;
                c.height = 460;
                const ctx = c.getContext('2d');

                ctx.fillStyle = '#fff';
                ctx.fillRect(0, 0, c.width, c.height);

                ctx.fillStyle = '#000';
                ctx.font = 'bold 22px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(epc, c.width / 2, 40);

                const qrImg = new Image();
                qrImg.onload = function() {
                    const size = 320;
                    ctx.drawImage(qrImg, (c.width - size) / 2, 70, size, size);

                    ctx.font = '14px Arial';
                    ctx.fillStyle = '#333';
                    ctx.fillText('Scan untuk Inbound', c.width / 2, 70 + size + 30);

                    const fileName = `QR_${epc.replace(/[^a-z0-9_\-]+/gi, '_')}.png`;
                    const a = document.createElement('a');
                    a.href = c.toDataURL('image/png');
                    a.download = fileName;
                    a.click();
                };
                qrImg.src = qrDataUrl;
            }

            $('#btn-generate-qr').on('click', function() {
                const epc = getEpc();
                if (!epc) return Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'EPC wajib diisi dulu'
                });
                generateQr(epc);
            });

            const initial = getEpc();
            if (initial) generateQr(initial);

            $('#btn-print-qr').on('click', function() {
                const epc = getEpc();
                if (!epc) return;

                const qrDataUrl = getQrDataUrl();
                if (!qrDataUrl) return Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'QR belum tergenerate'
                });

                downloadLabel(epc, qrDataUrl);
            });

        });
    </script>
@endpush
