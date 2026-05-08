@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">{{ $subtitle }}</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-primary alert-dismissible fade show mt-3">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sales-orders.delete') }}" method="POST" id="form-delete-selected"
                data-use-loader="true">
                @csrf
                <div class="d-flex mb-3">
                    <a href="{{ route('sales-orders.create') }}" class="btn round btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New
                    </a>

                    <button type="submit" id="btn-delete-selected" class="btn btn-danger round ms-1" disabled>
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>

                    <button type="submit" id="real-submit" class="d-none"></button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="table1">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all"></th>
                                <th>No</th>
                                <th>Order No</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                {{-- <th>Total Qty</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $i => $o)
                                <tr>
                                    <td><input type="checkbox" class="row-check" value="{{ $o->id }}"></td>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $o->order_no }}</td>
                                    <td>{{ $o->customer_name }}</td>
                                    <td>{{ $o->order_date }}</td>
                                    <td><span class="badge bg-light-primary">{{ $o->status }}</span></td>
                                    {{-- <td>{{ (int) $o->total_qty }}</td> --}}
                                    <td>
                                        @if ($o->status == 'Open')
                                            <a href="{{ route('sales-orders.show', $o->id) }}"
                                                class="btn btn-sm btn-primary">Detail</a>
                                        @else
                                            <span>
                                                <i class="fas fa-check"></i>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const selectAll = $('#select-all');
            const btnDelete = $('#btn-delete-selected');
            const form = $('#form-delete-selected');
            const realSubmit = $('#real-submit');

            const getSelectedIds = () =>
                $('.row-check:checked').map((_, el) => el.value).get();

            const toggleButton = () =>
                btnDelete.prop('disabled', getSelectedIds().length === 0);

            selectAll.on('change', function() {
                $('.row-check').prop('checked', this.checked);
                toggleButton();
            });

            $(document).on('change', '.row-check', function() {
                selectAll.prop(
                    'checked',
                    $('.row-check').length > 0 && $('.row-check').length === $('.row-check:checked')
                    .length
                );
                toggleButton();
            });

            btnDelete.on('click', function(e) {
                e.preventDefault();

                const ids = getSelectedIds();
                if (!ids.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tidak ada data yang dipilih'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: `Akan menghapus ${ids.length} order`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    // bersihkan input lama
                    form.find('input[name="selected_orders[]"][data-hidden="1"]').remove();

                    // inject input baru
                    ids.forEach(id => {
                        $('<input>', {
                            type: 'hidden',
                            name: 'selected_orders[]',
                            value: id,
                            'data-hidden': 1
                        }).appendTo(form);
                    });

                    realSubmit.click();
                });
            });

            $('#table1 thead th')
                .has('input[type="checkbox"]')
                .find('.dataTable-sorter')
                .removeClass('dataTable-sorter');
        });
    </script>
@endpush
