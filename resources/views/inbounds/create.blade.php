@extends('layouts.main-layouts')
@section('content')
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Inbound</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('inbounds.index') }}">Inbound</a></li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                @php
                    $isEdit = isset($inbound) && $inbound;
                @endphp

                <form method="POST" action="{{ $isEdit ? route('inbounds.update', $inbound->id) : route('inbounds.store') }}"
                    data-use-loader="true">
                    @csrf
                    @if ($isEdit)
                        @method('PUT')
                    @endif
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="roundText">Inbound Date <span class="text-danger">*</span></label>
                                    <input type="date" name="inbound_date" class="form-control"
                                        value="{{ old('inbound_date', $isEdit ? $inbound->inbound_date : '') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="product">Product <span class="text-danger">*</span></label>
                                    <select name="product_id" class="form-select">
                                        <option value="">-- Select Product --</option>

                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $isEdit ? $inbound->product_id : '') == $product->id ? 'selected' : '' }}>

                                                {{ $product->product_name }}

                                                @if($product->material)
                                                    - {{ $product->material->material_name }}
                                                @endif

                                                @if($product->color)
                                                    / {{ $product->color->color_name }}
                                                @endif

                                                @if($product->size)
                                                    / {{ $product->size->size_name }}
                                                @endif

                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="roundText">Quantity <span class="text-danger">*</span></label>
                                    <input type="text" id="roundText"
                                        class="form-control round @error('quantity') is-invalid @enderror" placeholder="quantity"
                                        name="quantity" value="{{ old('quantity', $isEdit ? $inbound->quantity : '') }}" />
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-12 mt-3">
                                <a href="{{ route('inbounds.index') }}" class="btn btn-outline-secondary">Back</a>
                                <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
@endsection