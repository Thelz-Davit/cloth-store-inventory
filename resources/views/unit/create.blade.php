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
        @php
            $isEdit = isset($unit) && $unit;
        @endphp

        <form method="POST" action="{{ $isEdit ? route('unit.update', $unit->id) : route('unit.store') }}"
            data-use-loader="true">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Code <span class="text-danger">*</span></label>
                            <input type="text" id="roundText"
                                class="form-control round @error('code') is-invalid @enderror" placeholder="Unit Code"
                                name="code" value="{{ old('code', $isEdit ? $unit->code : '') }}" />
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Unit Name <span class="text-danger">*</span></label>
                            <input type="text" id="roundText"
                                class="form-control round @error('name') is-invalid @enderror" placeholder="Unit Name"
                                name="name" value="{{ old('name', $isEdit ? $unit->name : '') }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Symbol <span class="text-danger">*</span></label>
                            <input type="text" id="roundText"
                                class="form-control round @error('symbol') is-invalid @enderror" placeholder="Symbol"
                                name="symbol" value="{{ old('symbol', $isEdit ? $unit->symbol : '') }}" />
                            @error('symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">Back</a>
                        <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
