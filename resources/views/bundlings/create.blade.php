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
    <form action="{{ $isEdit ? route('bundlings.update', $bundle->id) : route('bundlings.store') }}" method="POST">
        @csrf

        @if($isEdit)
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Bundling Name <span class="text-danger">*</span></label>
                            <input type="text" name="bundle_name" class="form-control"
                                value="{{ old('bundle_name', $isEdit ? $bundle->bundle_name : '') }}">
                            @error('color_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div id="items-container">

                        @if($isEdit)

                            @foreach($bundle->products as $i => $product)

                                <div class="item-row row g-2 mb-3">

                                    <div class="col-md-8">

                                        <select name="items[{{ $i }}][product_id]" class="form-select">

                                            <option value="">-- Select Product --</option>

                                            @foreach($products as $p)

                                                <option value="{{ $p->id }}" {{ $p->id == $product->id ? 'selected' : '' }}>

                                                    {{ $p->product_name }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="col-md-2">

                                        <input type="number" name="items[{{ $i }}][quantity]" class="form-control"
                                            value="{{ $product->pivot->quantity }}" min="1">

                                    </div>

                                    <div class="col-md-2">

                                        <button type="button" class="btn btn-danger remove-item">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </div>

                            @endforeach

                        @else

                            {{-- Your existing first row --}}

                        @endif

                    </div>
                    <button type="button" id="add-item" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Item
                    </button>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/" class="btn btn-secondary">
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
        </div>
        </form>
@endsection


@push('scripts')
    <script>
        const productOptions = `
    <option value="">-- Select Product --</option>
    @foreach($products as $product)
        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
    @endforeach
    `;

        let index = {{ $isEdit ? $bundle->products->count() : 1 }};

        const container = document.getElementById('items-container');

        document.getElementById('add-item').addEventListener('click', function () {

            const row = document.createElement('div');
            row.className = 'item-row row g-2 mb-3';

            row.innerHTML = `
            <div class="col-md-8">
                <select name="items[${index}][product_id]" class="form-select product-select">
                    ${productOptions}
                </select>
            </div>

            <div class="col-md-2">
                <input type="number"
                       name="items[${index}][quantity]"
                       class="form-control"
                       value="1"
                       min="1">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

            container.appendChild(row);
            refreshOptions();
            index++;
        });
    </script>
@endpush