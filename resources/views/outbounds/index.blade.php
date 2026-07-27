@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Outbound</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Outbound</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                @if (Auth::user()->role === 'staff' || Auth::user()->role === 'Staff Gudang' || Auth::user()->role === 'Superadmin')
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
                            <th>Materials / Items</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            @if (Auth::user()->role === 'staff' || Auth::user()->role === 'Staff Gudang' || Auth::user()->role === 'Superadmin')
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($outbounds as $outbound)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($outbound->outbound_date)->format('d-m-Y') }}</td>
                                <td>{{ $outbound->bundle->bundle_name ?? '-' }}</td>
                                <td>
                                    {{-- Menggunakan relasi bundleItems/materials yang baru --}}
                                    @if ($outbound->bundle && $outbound->bundle->bundleItems && $outbound->bundle->bundleItems->count() > 0)
                                        @foreach ($outbound->bundle->bundleItems as $item)
                                            <div>
                                                • {{ $item->material->material_name ?? 'Material N/A' }} 
                                                <span class="text-muted">({{ $item->quantity }}x)</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $outbound->quantity }}</td>
                                <td>
                                    @if($outbound->status == 'completed')
                                        <span class="badge bg-success-subtle text-success">{{ $outbound->status }}</span>
                                    @elseif($outbound->status == 'draft')
                                        <span class="badge bg-warning-subtle text-warning">{{ $outbound->status }}</span>
                                    @else
                                        <span class="badge bg-secondary-subtle">{{ $outbound->status ?? 'completed' }}</span>
                                    @endif
                                </td>
                                @if (Auth::user()->role === 'staff' || Auth::user()->role === 'Staff Gudang' || Auth::user()->role === 'Superadmin')
                                <td>
                                    <a href="{{ route('outbounds.edit', $outbound->id) }}" class="">
                                        <i class="bi bi-pencil text-warning"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger-subtle btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        onclick="setDeleteForm('{{ route('outbounds.destroy', $outbound->id) }}', '{{ $outbound->bundle->bundle_name ?? 'Outbound Item' }}')">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Outbound</h5>
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