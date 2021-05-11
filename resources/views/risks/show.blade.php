@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $risk->name])
                <div class="badge badge-{{ $levelClasses[$risk->level] }}">
                    <span>{{ __('risks.levels.' . $risk->level) }}</span>
                </div>

                <div class="badge badge-{{ $statusClasses[$risk->status] }} float-right">
                    <span>{{ __('risks.statuses.' . $risk->status) }}</span>
                </div>
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('risks.name') }}</legend>
                <span>{{ $risk->name }}</span>
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('risks.description') }}</legend>
                <span>{{ $risk->description }}</span>
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('risks.created_at') }}</legend>
                <span>{{ $risk->created_at->format('d.m.Y') }}</span>
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('risks.expired_at') }}</legend>
                <span>{{ $risk->expired_at->format('d.m.Y') }}</span>
            </div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-3">
                <legend>{{ __('risks.summa') }}</legend>
                <span>{{ $risk->summa }}</span>
            </div>
            <div class="form-group col-lg-3">
                <legend>{{ __('risks.damage') }}</legend>
                <span>{{ $risk->damage }}</span>
            </div>
            <div class="form-group col-lg-3">
                <legend>{{ __('risks.likelihood') }}</legend>
                <span>{{ $risk->likelihood }}</span>
            </div>
            <div class="form-group col-lg-3">
                <legend>{{ __('risks.impact') }}</legend>
                <span>{{ $risk->impact }}</span>
            </div>
        </div>

        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('factors.factors') }}</legend>
                @if($risk->factors->isNotEmpty())
                    <ul>
                        @foreach($risk->factors as $factor)
                            <li>{{ $factor->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="form-group col-lg-6">
                <legend>{{ __('types.types') }}</legend>
                @if($risk->types->isNotEmpty())
                    <ul>
                        @foreach($risk->types as $type)
                            <li>{{ $type->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card-body row">
            <div class="form-group col-lg-6">
                <legend>{{ __('divisions.division') }}</legend>
                <span>{{ optional($risk->division)->name ?? '' }}</span>
            </div>
        </div>
        <div class="form-group row ml-3">
            @include('common.actions', ['entityName' => 'risk', 'entity' => $risk])
        </div>
    </div>
@endsection
