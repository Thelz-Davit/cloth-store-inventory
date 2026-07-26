@extends('layouts.main-layouts')
@section('content')
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>{{ $title }}</h3>
                        <p class="text-subtitle text-muted">
                            {{ $subtitle }}
                        </p>
                    </div>
                    @php
    $breadcrumbs = $breadcrumbs ?? [];
                    @endphp
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                @foreach ($breadcrumbs as $i => $bc)
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
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable" id="table1">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th>SKU</th>
                                <th>Product</th>
                                <th>Qty Change</th>
                                <th>Type</th>
                                {{-- <th>Ref ID</th> --}}
                                <th>Created By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $i => $row)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td>{{ $row->sku }}</td>
                                    <td>{{ $row->product_name }}</td>

                                    <td>
                                        @if ($row->qty_change > 0)
                                            <span class="badge bg-light-success">+{{ $row->qty_change }}</span>
                                        @elseif ($row->qty_change < 0)
                                            <span class="badge bg-light-danger">{{ $row->qty_change }}</span>
                                        @else
                                            <span class="badge bg-light-secondary">0</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($row->ref_type == 'INBOUND')
                                            <span class="badge bg-light-primary">INBOUND</span>
                                        @elseif ($row->ref_type == 'OUTBOUND')
                                            <span class="badge bg-light-warning">OUTBOUND</span>
                                        @else
                                            <span class="badge bg-light-info">{{ $row->ref_type }}</span>
                                        @endif
                                    </td>

                                    {{-- <td>{{ $row->ref_id }}</td> --}}
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
