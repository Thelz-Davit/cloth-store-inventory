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

    {{-- Materials Section --}}
    <div class="col">
        <div class="card">
            <div class="card-body">
                <div class="d-flex mb-3 justify-content-between">
                    <h4>Materials</h4>
                    @if (Auth::user()->role === 'staff')
                    <a href="{{ route('materials.create') }}" class="btn round btn-primary">
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
                                            <a href="{{ route('materials.edit', $material->id) }}" class="btn btn-sm btn-warning-subtle">
                                                <i class="bi bi-pencil text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger-subtle btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                onclick="setDeleteForm('{{ route('materials.destroy', $material->id) }}', '{{ $material->material_name }}')">
                                                <i class="bi bi-trash text-danger"></i>
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
        {{-- Sizes Section --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3 justify-content-between">
                        <h4>Sizes</h4>
                        @if (Auth::user()->role === 'staff')
                        <a href="{{ route('sizes.create') }}" class="btn round btn-primary">
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
                                            <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-sm btn-warning-subtle">
                                                <i class="bi bi-pencil text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger-subtle btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                onclick="setDeleteForm('{{ route('sizes.destroy', $size->id) }}', '{{ $size->size_name }}')">
                                                <i class="bi bi-trash text-danger"></i>
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

        {{-- Colors Section --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex mb-3 justify-content-between">
                        <h4>Colors</h4>
                        @if (Auth::user()->role === 'staff')
                        <a href="{{ route('colors.create') }}" class="btn round btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New Color
                        </a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable" id="table4">
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
                                            <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-sm btn-warning-subtle">
                                                <i class="bi bi-pencil text-warning"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger-subtle btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                onclick="setDeleteForm('{{ route('colors.destroy', $color->id) }}', '{{ $color->color_name }}')">
                                                <i class="bi bi-trash text-danger"></i>
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

    {{-- Delete Modal Confirmation --}}
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
                        Are you sure you want to delete <strong id="deleteItemName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function setDeleteForm(url, name) {
            document.getElementById('deleteForm').action = url;
            document.getElementById('deleteItemName').textContent = name;
        }
    </script>
@endpush