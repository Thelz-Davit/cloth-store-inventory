@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $subtitle }}</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Outbound No</th>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Shipped At</th>
                            <th>Total Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $r)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $r->outbound_no }}</td>
                                <td>{{ $r->order_no }}</td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->shipped_at }}</td>
                                <td>{{ (int) $r->total_qty }}</td>
                                <td>
                                    <a href="{{ route('outbound.show', $r->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada outbound</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
