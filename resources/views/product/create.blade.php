@extends('layouts.main-layouts')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        {{-- <h3>{{ $title }}</h3> --}}
                        <p class="text-subtitle text-muted">
                            {{-- {{ $subtitle }} --}}
                        </p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                {{-- @foreach ($breadcrumbs as $i => $bc)
                                    @php $isLast = $i === count($breadcrumbs) - 1; @endphp

                                    @if ($isLast)
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{ $bc['label'] }}
                                        </li>
                                    @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $bc['url'] ?? '#' }}">{{ $bc['label'] }}</a>
                                        </li>
                                    @endif
                                @endforeach --}}
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            {{-- <div class="card-header">
                <h4 class="card-title">#</h4>
            </div> --}}
            @php
    $isEdit = isset($product) && $product;
            @endphp

            <form method="POST" action="{{ $isEdit ? route('product.update', $product->id) : route('product.store') }}"
                data-use-loader="true">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="roundText">Product Name <span class="text-danger">*</span></label>
                                <input type="text" id="roundText"
                                    class="form-control round @error('name') is-invalid @enderror" placeholder="Product Name"
                                    name="product_name" value="{{ old('name', $isEdit ? $product->product_name : '') }}" />
                                {{-- @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror --}}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="unit_code">Unit <span class="text-danger">*</span></label>
                                <select id="unit_code" name="unit_code"
                                    class="form-select round @error('unit_code') is-invalid @enderror">
                                    <option value="">-- Select Stasus --</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 mt-3">
                            <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">Back</a>
                            <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
@endsection
