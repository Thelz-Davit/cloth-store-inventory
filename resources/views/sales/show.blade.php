@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $subtitle }}</p>

        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show mt-3">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div><b>Order No:</b> {{ $order->order_no }}</div>
            <div><b>Customer:</b> {{ $order->customer_name }}</div>
            <div><b>Date:</b> {{ $order->order_date }}</div>
            <div><b>Status:</b> {{ $order->status }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form method="POST" action="{{ route('sales-orders.items.add', $order->id) }}" class="row g-2"
                data-use-loader="true">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">-- Select Product --</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}">{{ $p->sku }} - {{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Qty *</label>
                    <input type="number" name="qty" min="1" class="form-control" value="1" required>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">Add Item</button>
                </div>
            </form>

            <hr>

            <div class="table-responsive">
                <table class="table" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $i => $it)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $it->sku }}</td>
                                <td>{{ $it->product_name }}</td>
                                <td>{{ (int) $it->qty }}</td>
                                <td>
                                    <form method="POST" action="{{ route('sales-orders.items.delete', $it->id) }}"
                                        class="form-delete-item" data-name="{{ $it->sku }} - {{ $it->product_name }}"
                                        data-use-loader="true">
                                        @csrf
                                        <button class="btn btn-sm btn-danger btn-delete" type="button">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada item</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('sales-orders.index') }}" class="btn btn-light">Back</a>
                <a href="{{ route('outbound.index') }}" class="btn btn-success ms-1">Go to Outbound</a>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).on('click', '.form-delete-item .btn-delete', function(e) {
                e.preventDefault();

                const form = $(this).closest('form')[0];
                const name = $(form).data('name') || 'item';

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: `Item "${name}" akan dihapus dari order.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                }).then((res) => {
                    if (!res.isConfirmed) return;

                    if (form.requestSubmit) form.requestSubmit();
                    else form.submit();
                });
            });
        });
    </script>
@endpush
