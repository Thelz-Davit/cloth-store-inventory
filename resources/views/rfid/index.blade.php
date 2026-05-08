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
            <form action="{{ route('rfid-tags.delete') }}" method="POST" id="form-delete-selected" data-use-loader="true">
                @csrf

                <div class="d-flex mb-3">
                    <a href="{{ route('rfid-tags.create') }}" class="btn round btn-primary">
                        <i class="bi bi-plus-lg"></i> Add New RFID Tag
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
                                <th>EPC</th>
                                <th>Product Name </th>
                                <th>Status</th>
                                <th>State</th>
                                <th>Qty</th>
                                <th>QR</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rfids as $index => $rfid)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="row-check" value="{{ $rfid->id }}">
                                    </td>
                                    <td>{{ $rfid->epc }}</td>
                                    <td>{{ $rfid->product_name }}</td>
                                    <td>
                                        @if ($rfid->status == 'Active')
                                            <span class="badge bg-light-success">Active</span>
                                        @elseif($rfid->status == 'Damaged')
                                            <span class="badge bg-light-warning">Damaged</span>
                                        @else
                                            <span class="badge bg-light-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($rfid->state == 'New')
                                            <span class="badge bg-light-primary">New</span>
                                        @elseif($rfid->state == 'In_Stock')
                                            <span class="badge bg-light-info">In Stock</span>
                                        @else
                                            <span class="badge bg-light-danger">Out</span>
                                        @endif
                                    </td>
                                    <td>{{ $rfid->qty }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light-primary btn-show-qr"
                                            data-epc="{{ $rfid->epc }}">
                                            QR
                                        </button>
                                    </td>

                                    <td>
                                        @if (!in_array($rfid->state, ['In_Stock', 'Out']))
                                            <a href="{{ route('rfid-tags.edit', $rfid->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled
                                                title="Tag sudah In Stock, tidak bisa diubah">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (collect($rfids)->where('state', 'In_Stock')->count() > 0)
                        <p class="text-muted mt-2 mb-0">
                            <i class="bi bi-info-circle"></i>
                            Tag sudah <b>In Stock</b>, tidak bisa diubah.
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
    {{-- QR Modal --}}
    <div class="modal fade" id="modalQR" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrModalBox" class="d-flex justify-content-center"></div>
                    <div class="mt-2"><b id="qrText"></b></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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

                    form.find('input[name="selected_tags[]"][data-hidden="1"]').remove();

                    ids.forEach(id => {
                        $('<input>', {
                            type: 'hidden',
                            name: 'selected_tags[]',
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

        $(function() {
            $(document).on('click', '.btn-show-qr', function() {
                const epc = $(this).data('epc');
                $('#qrText').text(epc);

                const el = document.getElementById('qrModalBox');
                el.innerHTML = '';
                new QRCode(el, {
                    text: epc,
                    width: 240,
                    height: 240
                });

                const modal = new bootstrap.Modal(document.getElementById('modalQR'));
                modal.show();
            });
        });
    </script>
@endpush
