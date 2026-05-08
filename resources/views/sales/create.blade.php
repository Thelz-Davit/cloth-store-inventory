@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $subtitle }}</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('sales-orders.store') }}" data-use-loader="true">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Customer Name *</label>
                        <input name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                            value="{{ old('customer_name') }}">
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Order Date *</label>
                        <input type="datetime-local" name="order_date"
                            class="form-control @error('order_date') is-invalid @enderror"
                            value="{{ old('order_date', now()->format('Y-m-d\TH:i')) }}">
                        @error('order_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Phone</label>
                        <input name="customer_phone" class="form-control" value="{{ old('customer_phone') }}">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label class="form-label">Address</label>
                        <input name="customer_address" class="form-control" value="{{ old('customer_address') }}">
                    </div>

                    <div class="col-12 mt-2">
                        <label class="form-label">Note</label>
                        <textarea name="note" class="form-control" value="{{ old('note') }}"></textarea>
                    </div>
                </div>

                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('sales-orders.index') }}" class="btn btn-light">Cancel</a>
                    <button class="btn btn-primary" type="submit">Create Order</button>
                </div>
            </form>
        </div>
    </div>
@endsection
