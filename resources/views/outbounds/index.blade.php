@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Outbounds</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Outbounds</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                @if (Auth::user()->role === 'staff')
                <a href="{{ route('outbounds.create') }}" class="btn round btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>
                @endif
                <button type="submit" id="real-submit" class="d-none"></button>
            </div>
            <div class="table-responsive">
                <table class="table datatable" id="table1">
                    <thead>
                        <tr>
                            <th>Outbound Date</th>
                            <th>Bundle Name</th>
                            <th>Products</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            @if (Auth::user()->role === 'staff')

                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outbounds as $outbound)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($outbound->outbound_date)->format('d-m-Y') }}</td>
                                <td>{{ $outbound->bundle->bundle_name }}</td>
                                <td>
                                    @foreach ($outbound->bundle->products as $product)
                                        <div>
                                            {{ $product->product_name }}
                                            ({{ $product->pivot->quantity }}x)

                                            @if($product->material)
                                                - {{ $product->material->material_name }}
                                            @endif

                                            @if($product->color)
                                                / {{ $product->color->color_name }}
                                            @endif

                                            @if($product->size)
                                                / {{ $product->size->size_name }}
                                            @endif
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ $outbound->quantity }}</td>
                                <td>
                                    @if($outbound->status == 'completed')
                                        <span class="badge bg-success">{{ $outbound->status }}</span>
                                    @elseif($outbound->status == 'draft')
                                        <span class="badge bg-warning text-dark">{{ $outbound->status }}</span>
                                    @endif
                                </td>
                                @if (Auth::user()->role === 'staff')
                                <td>
                                    <a href="{{ route('outbounds.edit', $outbound->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="setDeleteForm('{{ route('outbounds.destroy', $outbound->id) }}', '{{ $outbound->bundle->bundle_name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete outbound</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    Are you sure you want to delete
                                    <strong id="outboundName"></strong>?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Cancel
                                    </button>

                                    <button type="submit" class="btn btn-danger">
                                        Delete
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function setDeleteForm(url, name) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('outboundName').textContent = name;
        }
    </script>
@endpush