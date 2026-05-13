@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    {{-- <h3>{{ $title }}</h3> --}}
                    <p class="text-subtitle text-muted">
                        {{-- {{ $subtitle }} --}}
                    </p>
                </div>
                {{-- @php
                    $breadcrumbs = $breadcrumbs ?? [];
                @endphp --}}
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            {{-- @foreach ($breadcrumbs as $i => $bc)
                                @php $isLast = $i === count($breadcrumbs) - 1; @endphp

                                @if ($isLast)
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {{ $bc['label'] }}
                                    </li>
                                @else
                                    <li class="breadcrumb-item">
                                        <a href="{{ $bc['url'] ?? '#' }}">{{ $bc['label'] }}</a>
                                    </li>
                                @endif
                            @endforeach --}}
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.delete') }}" method="POST" id="form-delete-selected" data-use-loader="true">
                @csrf

                <div class="d-flex mb-3">
                    <a href="{{ route('product.create') }}" class="btn round btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New
                    </a>

                    <button type="button" id="btn-delete-selected" class="btn btn-danger round ms-1" disabled>
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
                                <th>Product Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-check" value="{{ $product->id }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->status }}</td>
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- @   push('scripts') --}}
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
                e.stopPropagation();
                e.preventDefault();

                const ids = getSelectedIds();

                if (!ids.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tidak ada data yang dipilih',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: `Akan menghapus ${ids.length} data`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#d33',
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    form.find('input[name="selected_products[]"][data-hidden="1"]').remove();

                    ids.forEach(id => {
                        $('<input>', {
                            type: 'hidden',
                            name: 'selected_products[]',
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
{{-- @endpush --}}
