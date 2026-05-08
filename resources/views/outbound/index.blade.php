@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">{{ $subtitle }}</p>
                </div>
                @php $breadcrumbs = $breadcrumbs ?? []; @endphp
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $i => $bc)
                                @php $isLast = $i === count($breadcrumbs) - 1; @endphp
                                @if ($isLast)
                                    <li class="breadcrumb-item active" aria-current="page">{{ $bc['label'] }}</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{ $bc['url'] ?? '#' }}">{{ $bc['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show mt-3" role="alert">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Order No</th>
                            <th>Customer</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $i => $o)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $o->order_no }}</td>
                                <td>{{ $o->customer_name ?? '-' }}</td>
                                <td>{{ $o->order_date ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-light-primary">{{ $o->status ?? 'Open' }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('outbound.process', $o->id) }}" class="btn btn-sm btn-primary">
                                        Process
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada order</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
