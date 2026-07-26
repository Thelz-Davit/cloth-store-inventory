@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Master Data</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Master Data</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

        <div class=" col">
            <div class="card">
                <div class="card-body">
                        <div class="d-flex mb-3 justify-content-between">
                            <h4>Materials</h4>
                            @if (Auth::user()->role === 'staff')
                            <a href="{{ route('master-data.materials.create') }}" class="btn round btn-primary">
                                <i class="bi bi-plus-lg"></i> Add New Material
                            </a>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table datatable" id="table1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Materials</th>
                                        @if (Auth::user()->role === 'staff')
                                    
                                        <th>Actions</th>
                                @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($materials as $index => $material)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $material->material_name }}</td>
                                            @if (Auth::user()->role === 'staff')

                                            <td>
                                                <a href="{{ route('master-data.materials.edit', $material->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-delete btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-url="{{ route('master-data.materials.destroy', $material) }}" data-name="{{ $material->material_name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                            <div class="d-flex mb-3 justify-content-between">
                                <h4>Sizes</h4>
                                @if (Auth::user()->role === 'staff')
                                <a href="{{ route('master-data.sizes.create') }}" class="btn round btn-primary">
                                    <i class="bi bi-plus-lg"></i> Add New Size
                                </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable" id="table3">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Size</th>
                                            @if (Auth::user()->role === 'staff')

                                            <th>Actions</th>
                                @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sizes as $index => $size)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $size->size_name }}</td>
                                                @if (Auth::user()->role === 'staff')

                                                <td>
                                                    <a href="{{ route('master-data.sizes.edit', $size->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-danger btn-delete btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-url="{{ route('master-data.sizes.destroy', $size) }}" data-name="{{ $size->size_name }}">
                                                    <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
            {{-- Color --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                            <div class="d-flex mb-3 justify-content-between">
                                <h4>Colors</h4>
                                @if (Auth::user()->role === 'staff')
                                <a href="{{ route('master-data.colors.create') }}" class="btn round btn-primary">
                                    <i class="bi bi-plus-lg"></i> Add New Color
                                </a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table datatable" id="table3">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Color</th>
                                            @if (Auth::user()->role === 'staff')

                                            <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($colors as $index => $color)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $color->color_name }}</td>
                                                @if (Auth::user()->role === 'staff')

                                                <td>
                                                    <a href="{{ route('master-data.colors.edit', $color->id) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-delete btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-url="{{ route('master-data.colors.destroy', $color) }}" data-name="{{ $color->color_name }}">
                                                    <i class="bi bi-trash"></i>

                                                    </button>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title">Delete Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to delete
                        <strong id="deleteItemName"></strong>?
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
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('deleteForm').action = this.dataset.url;
                    document.getElementById('deleteItemName').textContent = this.dataset.name;
                });
            });
    </script>
@endpush