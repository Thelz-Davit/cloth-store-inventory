@extends('layouts.main-layouts')
@section('content')
                                <div class="page-heading">
                                    <div class="page-title">
                                        <div class="row">
                                            <div class="col-12 col-md-6 order-md-1 order-last">
                                                <h3>Products</h3>
                                            </div>
                                            <div class="col-12 col-md-6 order-md-2 order-first">
                                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                                    <ol class="breadcrumb">
                                                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                                                    </ol>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    @php
    $isEdit = isset($product) && $product;
                                    @endphp

                                    <form method="POST" action="{{ $isEdit ? route('products.update', $product->id) : route('products.store') }}"
                                        data-use-loader="true" onsubmit="console.log('Submitting');">
                                        @csrf
                                        @if ($isEdit)
                                            @method('PUT')
                                        @endif
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h4 class="mb-0">Products</h4>
                                                <div class="form-check form-switch mb-0">
                                                    <input type="hidden" name="status" value="0">
                                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $isEdit ? $product->status : true) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="roundText">Product Name <span class="text-danger">*</span></label>
                                                        <input type="text" id="roundText" class="form-control round @error('product_name') is-invalid @enderror"
                                                            placeholder="Product Name" name="product_name"
                                                            value="{{ old('product_name', $isEdit ? $product->product_name : '') }}" />
                                                        @error('product_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="material">Size <span class="text-danger">*</span></label>
                                                        <select id="material" name="material_id" class="form-select round @error('material_id') is-invalid @enderror">
                                                            <option value="" selected disabled>-- Select Material --</option>
                                                            <option value="">-- No Material --</option>
                                                            @foreach ($materials as $material)
                                                                <option value="{{ $material->id }}" {{ old('material_name', $isEdit ? $product->material : '') == $material->material_name ? 'selected' : '' }}>
                                                                    {{ $material->material_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- fetch size dari db --}}
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="size">Size</label>
                                                        <select id="size" name="size_id" class="form-select round @error('size_id') is-invalid @enderror">
                                                            <option value="" selected disabled>-- Select Size --</option>
                                                            <option value="">-- No Material --</option>
                                                            @foreach ($sizes as $size)
                                                                <option value="{{ $size->id }}" {{ old('size_name', $isEdit ? $product->size : '') == $size->size_name ? 'selected' : '' }}>
                                                                    {{ $size->size_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- fetch color dari db --}}
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="color">Warna</span></label>
                                                        <select id="color" name="color_id" class="form-select round @error('color_name') is-invalid @enderror">
                                                            <option value="" selected disabled>-- Select Color --</option>
                                                            <option value="">-- No Material --</option>
                                                            @foreach ($colors as $color)
                                                                <option value="{{ $color->id }}" {{ old('color_name', $isEdit ? $product->color : '') == $color->color_name ? 'selected' : '' }}>
                                                                    {{ $color->color_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>

                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($product) ? $product->status : true) == true ? 'checked' : '' }}>

                                                        </div>

                                                        @error('status')
                                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div> --}}

                                                <div class="col-sm-12 mt-3">
                                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Back</a>
                                                    <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
@endsection