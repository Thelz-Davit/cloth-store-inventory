@extends('layouts.main-layouts')
@section('content')
                                        <div class="page-heading">
                                            <div class="page-title">
                                                <div class="row">
                                                    <div class="col-12 col-md-6 order-md-1 order-last">
                                                        <h3>outbound</h3>
                                                    </div>
                                                    <div class="col-12 col-md-6 order-md-2 order-first">
                                                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                                            <ol class="breadcrumb">
                                                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                                                <li class="breadcrumb-item"><a href="/">outbound</a></li>
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

                                            <form id="outboundForm" method="POST" action="{{ $isEdit ? route('outbounds.update', $outbound->id) : route('outbounds.store') }}"
                                                data-use-loader="true">
                                                @csrf
                                                @if ($isEdit)
                                                    @method('PUT')
                                                @endif
                                                @error('stock')
                                                        <div class="alert alert-danger">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                <input type="hidden" name="save_as_draft" id="save_as_draft" value="0">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="roundText">outbound Date <span class="text-danger">*</span></label>
                                                                <input type="date" name="outbound_date" class="form-control"
                                                                    value="{{ old('outbound_date', $isEdit ? $outbound->outbound_date : '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="bundle">bundle <span class="text-danger">*</span></label>
                                                                <select name="bundle_id" class="form-select">
                                                                    <option value="">-- Select Bundle --</option>

                                                                    @foreach($bundles as $bundle)
                                                                        <option value="{{ $bundle->id }}" {{ old('bundle_id', $isEdit ? $outbound->bundle_id : '') == $bundle->id ? 'selected' : '' }}>

                                                                            {{ $bundle->bundle_name }}

                                                                            (
                                                                            @foreach($bundle->products as $product)

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

                                                                                ×{{ $product->pivot->quantity }}

                                                                                @if(!$loop->last)
                                                                                    ,
                                                                                @endif

                                                                            @endforeach
                                                                            )

                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="roundText">Quantity <span class="text-danger">*</span></label>

                                                                <input type="number" name="quantity" class="form-control"
                                                                        value="{{ old('quantity', $isEdit ? $outbound->quantity : 1) }}" />
                                                                @error('quantity')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 mt-3">
                                                            <a href="{{ route('outbounds.index') }}" class="btn btn-outline-secondary">Back</a>
                                                            <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal fade" id="stockModal" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            Insufficient Stock
                                                        </h5>

                                                        <button class="btn-close" data-bs-dismiss="modal">
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <p>
                                                            The following products do not have enough stock.
                                                        </p>

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
                                                                        <td class="text-danger">
                                                                            {{ $item['available'] }}
                                                                        </td>

                                                                    </tr>

                                                                @endforeach

                                                            </tbody>

                                                        </table>
                                                        <button type="button" class="btn btn-primary" id="saveDraft">

                                                            Save as Draft

                                                        </button>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
@endsection
@push('scripts')
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