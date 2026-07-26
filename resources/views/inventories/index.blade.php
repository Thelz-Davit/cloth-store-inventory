@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Inventory</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory</li>
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
                            <th>Product Name</th>
                            <th>Material</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Status</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->material->material_name ?? '-'}}</td>
                                <td>{{ $product->color?->color_name ?? '-' }}</td>
                                <td>{{ $product->size?->size_name ?? '-' }}</td>
                                <td>
                                    @if($product->status)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                    @if($product->stock < 20)
                                        <span class="badge bg-warning text-dark ms-1">Low Stock</span>
                                    @else
                                        <span class="badge bg-success ms-1">In Stock</span>
                                    @endif
                                </td>

                                <td>{{ $product->stock }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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