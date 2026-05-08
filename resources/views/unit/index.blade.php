@extends('layouts.main-layouts')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ $title }}</h3>
                    <p class="text-subtitle text-muted">
                        {{ $subtitle }}
                    </p>
                </div>
                @php
                    $breadcrumbs = $breadcrumbs ?? [];
                @endphp
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbs as $i => $bc)
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
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('unit.delete') }}" method="POST" id="form-delete-selected" data-use-loader="true">
                @csrf

                <div class="d-flex mb-3">
                    <a href="{{ route('unit.create') }}" class="btn round btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New Unit
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
                                <th>Code</th>
                                <th>Unit</th>
                                <th>Symbol</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($units as $index => $unit)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-check" value="{{ $unit->id }}">
                                    </td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $unit->code }}</td>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->symbol }}</td>
                                    <td>
                                        <a href="{{ route('unit.edit', $unit->id) }}" class="btn btn-sm btn-primary">
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

                    form.find('input[name="selected_units[]"][data-hidden="1"]').remove();

                    ids.forEach(id => {
                        $('<input>', {
                            type: 'hidden',
                            name: 'selected_units[]',
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
