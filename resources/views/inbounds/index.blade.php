@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>inbounds</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">inbounds</li>
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
                <a href="{{ route('inbounds.create') }}" class="btn round btn-primary">
                    <i class="bi bi-plus-lg"></i> Add New
                </a>
                @endif
                <button type="submit" id="real-submit" class="d-none"></button>
            </div>
            <div class="table-responsive">
                <table class="table datatable" id="table1">
                    <thead>
                        <tr>
                            <th>inbound Date</th>
                            <th>Product Name</th>
                            <th>Material</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Quantity</th>
                            @if (Auth::user()->role === 'staff')

                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inbounds as $inbound)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($inbound->inbound_date)->format('d-m-Y') }}</td>
                                <td>{{ $inbound->product->product_name }}</td>
                                <td>{{ $inbound->product->material?->material_name ?? '-' }}</td>
                                <td>{{ $inbound->product->color?->color_name ?? '-' }}</td>
                                <td>{{ $inbound->product->size?->size_name ?? '-' }}</td>
                                <td>{{ $inbound->quantity }}</td>
                                @if (Auth::user()->role === 'staff')

                                <td>
                                    <a href="{{ route('inbounds.edit', $inbound->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="setDeleteForm('{{ route('inbounds.destroy', $inbound->id) }}', '{{ $inbound->product->product_name }}')">
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
                                    <h5 class="modal-title" id="deleteModalLabel">Delete inbound</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    Are you sure you want to delete
                                    <strong id="inboundName"></strong>?
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
            document.getElementById('inboundName').textContent = name;
        }
    </script>
@endpush