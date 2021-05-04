@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $division->name])
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('divisions.name') }}</legend>
                <span>{{ $division->name }}</span>
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('divisions.parent_id') }}</legend>
                <span>{{ optional($division->parent)->name ?? '-'}}</span>
            </div>
        </div>
    </div>
@endsection
