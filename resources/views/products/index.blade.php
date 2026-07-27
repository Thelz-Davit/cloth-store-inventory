@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Products</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Products</li>
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
                    <a href="{{ route('products.create') }}" class="btn round btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New
                    </a>
                    @endif

                    <button type="submit" id="real-submit" class="d-none"></button>
                </div>
                <div class="table-responsive">
                    <table class="table datatable" id="table1">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Material</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Status</th>
                                @if (Auth::user()->role === 'staff')
                                <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td>{{ $product->product_name}}</td>
                                    <td>{{ $product->material->material_name ?? '-'}}</td>
                                    <td>{{ $product->color?->color_name ?? '-' }}</td>
                                    <td>{{ $product->size?->size_name ?? '-' }}</td>
                                    <td>
                                        @if($product->status)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    @if (Auth::user()->role === 'staff')

                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning-subtle">
                                            <i class="bi bi-pencil text-warning"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger-subtle btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            onclick="setDeleteForm('{{ route('products.destroy', $product->id) }}', '{{ $product->product_name }}')">
                                            <i class="bi bi-trash text-danger"></i>
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form id="deleteForm" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Are you sure you want to delete
                                        <strong id="productName"></strong>?
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
                document.getElementById('productName').textContent = name;
            }
    </script>
@endpush
