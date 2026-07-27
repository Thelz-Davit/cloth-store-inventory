@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Bundling</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bundling</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Tampilkan Error Validasi jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('bundlings.update', $bundle->id) : route('bundlings.store') }}" method="POST">
        @csrf

        @if($isEdit)
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="roundText">Bundling Name <span class="text-danger">*</span></label>
                            <input type="text" name="bundle_name" class="form-control"
                                value="{{ old('bundle_name', $isEdit ? $bundle->bundle_name : '') }}">
                        </div>
                    </div>

                    <div id="items-container" class="col-sm-12">
                        @if($isEdit)
                            @foreach($bundle->materials as $i => $material)
                                <div class="item-row row g-2 mb-3">
                                    <div class="col-md-8">
                                        <select name="items[{{ $i }}][material_id]" class="form-select">
                                            <option value="">-- Select Material --</option>
                                            @foreach($materials as $p)
                                                <option value="{{ $p->id }}" {{ $p->id == $material->id ? 'selected' : '' }}>
                                                    {{ $p->material_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                                            value="{{ $material->pivot->quantity }}" min="1">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-item w-100">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- Baris Default Pertama untuk Mode Create --}}
                            <div class="item-row row g-2 mb-3">
                                <div class="col-md-8">
                                    <select name="items[0][material_id]" class="form-select">
                                        <option value="">-- Select Material --</option>
                                        @foreach($materials as $p)
                                            <option value="{{ $p->id }}">{{ $p->material_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][quantity]" class="form-control" value="1" min="1">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-item w-100">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-sm-12 mb-3">
                        <button type="button" id="add-item" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New Item
                        </button>
                    </div>
                </div>

                <div class="card border mt-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('bundlings.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Save Bundle
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const materialOptions = `
            <option value="">-- Select Material --</option>
            @foreach($materials as $material)
                <option value="{{ $material->id }}">{{ $material->material_name }}</option>
            @endforeach
        `;

        let index = {{ $isEdit ? $bundle->materials->count() : 1 }};
        const container = document.getElementById('items-container');

        // Tambah baris item baru
        document.getElementById('add-item').addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'item-row row g-2 mb-3';

            row.innerHTML = `
                <div class="col-md-8">
                    <select name="items[${index}][material_id]" class="form-select">
                        ${materialOptions}
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="items[${index}][quantity]" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-item w-100">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;

            container.appendChild(row);
            index++;
        });

        // Hapus baris item (Event Delegation)
        container.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item')) {
                // Pastikan minimal ada 1 baris yang tersisa
                if (container.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('.item-row').remove();
                } else {
                    alert('Bundling must have at least one item.');
                }
            }
        });
    </script>
@endpush    