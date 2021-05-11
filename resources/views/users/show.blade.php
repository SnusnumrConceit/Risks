@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $user->full_name])
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('users.last_name') }}</legend>
                <span>{{ $user->last_name }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('users.appointment') }}</legend>
                <span>{{ $user->appointment }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('users.first_name') }}</legend>
                <span>{{ $user->first_name }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('users.email') }}</legend>
                <span>{{ $user->email }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('users.middle_name') }}</legend>
                <span>{{ $user->middle_name }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('roles.role') }}</legend>
                <span>{{ optional($user->role)->name }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('divisions.division') }}</legend>
                <span>{{ optional($user->division)->name }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('users.responsible') }}</legend>
                <span>{{ $user->is_responsible ? 'Да' : 'Нет' }}</span>
            </div>
        </div>
        <div class="form-group row ml-3">
            @include('common.actions', ['entityName' => 'user', 'entity' => $user])
        </div>
    </div>
@endsection
