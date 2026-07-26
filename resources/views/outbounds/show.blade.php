@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $subtitle }}</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <b>Outbound No:</b> {{ $outbound->outbound_no }} <br>
                <b>Order No:</b> {{ $outbound->order_no }} <br>
                <b>Customer:</b> {{ $outbound->customer_name }} <br>
                <b>Shipped At:</b> {{ $outbound->shipped_at }}
            </div>

            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>EPC</th>
                            <th>Product</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $i => $it)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $it->epc }}</td>
                                <td>{{ $it->sku }} - {{ $it->product_name }}</td>
                                <td>{{ (int) $it->qty }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('outbound.history') }}" class="btn btn-light mt-3">Back</a>
        </div>
    </div>
@endsection
