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
            $isEdit = isset($user) && $user;
        @endphp

        <form method="POST" action="{{ $isEdit ? route('account.update', $user->id) : route('account.store') }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Name <span class="text-danger">*</span></label>
                            <input type="text" id="roundText"
                                class="form-control round @error('name') is-invalid @enderror" placeholder="Name"
                                name="name" value="{{ old('name', $isEdit ? $user->name : '') }}" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="roundText"
                                class="form-control round @error('email') is-invalid @enderror" placeholder="Email"
                                value="{{ old('email', $isEdit ? $user->email : '') }}" />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="role_id">Role <span class="text-danger">*</span></label>
                            <select id="role_id" name="role_id"
                                class="form-select round @error('role_id') is-invalid @enderror">
                                @if (!$isEdit)
                                    <option value="">-- pilih role --</option>
                                @endif
                                @foreach ($roles as $role)
                                    <option value="{{ $role->role_id }}"
                                        {{ old('role_id', $isEdit ? $user->role_id : '') == $role->role_id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Address</label>
                            <input type="text" id="roundText" class="form-control round" name="address"
                                placeholder="Address" value="{{ old('address', $isEdit ? $user->address : '') }}" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="roundText">Phone</label>
                            <input type="text" id="roundText"
                                class="form-control round @error('phone') is-invalid @enderror" name="phone"
                                placeholder="Phone" value="{{ old('phone', $isEdit ? $user->phone : '') }}" />
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <a href="{{ route('account.index') }}" class="btn btn-outline-secondary">Back</a>
                        <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Submit' }}</button>
                    </div>
                </div>
        </form>

        <small class="text-muted d-block mt-3">
            Password default user baru: <b>konoha</b>
        </small>
    </div>
@endsection
