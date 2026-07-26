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
            {{-- <div class="card-header">
                <h4 class="card-title">#</h4>
            </div> --}}
            <div class="card-body">
                <form action="{{ route('account.delete') }}" method="POST" id="form-delete-selected">
                    @csrf

                    <div class="d-flex mb-3">
                        <a href="{{ route('account.create') }}" class="btn round btn-primary">
                            <i class="bi bi-plus-lg"></i> Add New Account
                        </a>

                        <button type="submit" id="btn-delete-selected" class="btn btn-danger round ms-1" disabled>
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table datatable" id="table1">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row-check" value="{{ $user->id }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            <a href="{{ route('account.edit', $user->id) }}" class="btn btn-sm btn-primary">
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
                const ids = getSelectedIds();

                if (!ids.length) {
                    e.preventDefault();
                    alert('Tidak ada data dipilih');
                    return;
                }

                if (!confirm(`Yakin hapus ${ids.length} data?`)) {
                    e.preventDefault();
                    return;
                }

                form.find('input[name="selected_users[]"][data-hidden="1"]').remove();
                ids.forEach(id => {
                    $('<input>', {
                        type: 'hidden',
                        name: 'selected_users[]',
                        value: id,
                        'data-hidden': 1
                    }).appendTo(form);
                });
            });

            $('#table1 thead th')
                .has('input[type="checkbox"]')
                .find('.dataTable-sorter')
                .removeClass('dataTable-sorter');
        });
    </script>
@endpush
