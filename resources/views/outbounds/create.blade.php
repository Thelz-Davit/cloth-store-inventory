@extends('layouts.main-layouts')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Outbound</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('outbounds.index') }}">Outbound</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="card">
    @php
        $isEdit = isset($outbound) && $outbound;
    @endphp

    <form id="outboundForm" method="POST" action="{{ $isEdit ? route('outbounds.update', $outbound->id) : route('outbounds.store') }}" data-use-loader="true">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        @error('stock')
            <div class="alert alert-danger mx-4 mt-3">
                {{ $message }}
            </div>
        @enderror

        <input type="hidden" name="save_as_draft" id="save_as_draft" value="0">
        
        <div class="card-body">
            <div class="row">
                <!-- 1. Outbound Date -->
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="outbound_date">Outbound Date <span class="text-danger">*</span></label>
                        <input type="date" name="outbound_date" class="form-control"
                            value="{{ old('outbound_date', $isEdit ? $outbound->outbound_date : date('Y-m-d')) }}" required>
                    </div>
                </div>

                <!-- 2. Select Bundle -->
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="bundle_id">Bundle <span class="text-danger">*</span></label>
                        <select name="bundle_id" id="bundle_select" class="form-select" required>
                            <option value="">-- Select Bundle --</option>
                            @foreach($bundles as $bundle)
                                <option value="{{ $bundle->id }}" {{ old('bundle_id', $isEdit ? $outbound->bundle_id : '') == $bundle->id ? 'selected' : '' }}>
                                    {{ $bundle->bundle_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- 3. Quantity -->
                <div class="col-sm-6 mt-3">
                    <div class="form-group">
                        <label for="quantity">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="1"
                            value="{{ old('quantity', $isEdit ? $outbound->quantity : 1) }}" required />
                        @error('quantity')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- 4. Dynamic Material Variants Container -->
                <div class="col-sm-12 mt-4">
                    <div id="material_variants_container"></div>
                </div>

                <!-- Buttons -->
                <div class="col-sm-12 mt-4">
                    <a href="{{ route('outbounds.index') }}" class="btn btn-outline-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Insufficient Stock -->
<div class="modal fade" id="stockModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Insufficient Stock</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>The following products do not have enough stock to complete this transaction:</p>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Material</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Required</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('failedProducts', []) as $item)
                            <tr>
                                <td>{{ $item['product_name'] }}</td>
                                <td>{{ $item['material'] ?? '-' }}</td>
                                <td>{{ $item['color'] ?? '-' }}</td>
                                <td>{{ $item['size'] ?? '-' }}</td>
                                <td>{{ $item['required'] }}</td>
                                <td class="text-danger fw-bold">{{ $item['available'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="button" class="btn btn-warning" id="saveDraft">
                    Save as Draft
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bundleSelect = document.getElementById('bundle_select');
    const container = document.getElementById('material_variants_container');

    // Fungsi Fetch Detail Bundle via AJAX
    function loadBundleDetails(bundleId) {
        container.innerHTML = '<div class="text-center my-3"><div class="spinner-border text-primary"></div></div>';

        fetch(`/get-bundle-details/${bundleId}`)
            .then(response => response.json())
            .then(data => {
                container.innerHTML = ''; // Reset container

                if (!data.items || data.items.length === 0) {
                    container.innerHTML = '<div class="alert alert-warning">Resep bundle ini belum memiliki material dasar.</div>';
                    return;
                }

                let html = '<h5 class="mb-3">Pilih Varian Stok Fisik Gudang:</h5>';

                data.items.forEach(item => {
                    let material = item.material;
                    let products = material ? material.products : [];

                    html += `<div class="card mb-3 border p-3 bg-light">
                        <label class="fw-bold mb-2">${material ? material.material_name : 'Material N/A'} (Resep: ${item.quantity} pcs / bundle)</label>`;

                    if (products.length >= 1) {
                        // Cek apakah produk fisik dari material ini punya atribut warna atau size
                        let hasAttributes = products.some(p => p.color || p.size);

                        if (hasAttributes) {
                            // JIKA BERVARIAN (Memiliki Warna / Size) -> PAKSA KELUARKAN DROPDOWN
                            html += `<select name="selected_products[]" class="form-select" required>
                                <option value="">-- Pilih Warna & Ukuran --</option>`;
                            
                            products.forEach(prod => {
                                let colorName = prod.color ? prod.color.color_name : '-';
                                let sizeName = prod.size ? prod.size.size_name : '-';
                                html += `<option value="${prod.id}">Warna: ${colorName} | Size: ${sizeName} (Sisa Stok: ${prod.stock})</option>`;
                            });

                            html += `</select>`;
                        } else {
                            // JIKA TIDAK BERVARIAN (Seperti Sablon DTF yang gak ada warna/size) -> OTOMATIS PILIH
                            let prod = products[0];
                            html += `<input type="hidden" name="selected_products[]" value="${prod.id}">
                                <div class="text-muted"><small>✓ Otomatis menggunakan stok fisik ID #${prod.id} (Tersedia: ${prod.stock})</small></div>`;
                        }
                    } else {
                        // JIKA STOK KOSONG
                        html += `<div class="text-danger"><small>⚠️ Stok fisik untuk material ini KOSONG di gudang!</small></div>`;
                    }

                    html += `</div>`;
                });

                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching bundle details:', error);
                container.innerHTML = '<div class="alert alert-danger">Gagal mengambil data resep bundle.</div>';
            });
    }

    // Trigger saat dropdown bundle diubah
    bundleSelect.addEventListener('change', function() {
        if (this.value) {
            loadBundleDetails(this.value);
        } else {
            container.innerHTML = '';
        }
    });

    // Auto-load jika dalam mode edit atau ada value old
    if (bundleSelect.value) {
        loadBundleDetails(bundleSelect.value);
    }
});
</script>

@if(session('failedProducts'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = new bootstrap.Modal(document.getElementById('stockModal'));
    modal.show();

    document.getElementById('saveDraft').addEventListener('click', function () {
        document.getElementById('save_as_draft').value = 1;
        document.getElementById('outboundForm').submit();
    });
});
</script>
@endif
@endpush