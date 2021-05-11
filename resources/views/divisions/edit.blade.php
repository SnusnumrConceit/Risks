@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $division->name])
            </div>
        </div>
        <div class="card-body col-lg-4">
            <form action="{{ route('divisions.update', ['division' => $division]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">
                        {{ __('divisions.name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('name')) is-invalid @endif"
                           name="name"
                           id="name"
                           value="{{ old('name', $division->name) }}"
                           required
                    >
                    @if($errors->has('name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="parent_id">
                        {{ __('divisions.parent_id') }}
                    </label>
                    @include('risks.divisions_selector', [
                        'divisions' => $availableDivisions,
                        'name'      => 'parent_id',
                        'selected'  => $division->parent_id,
                        'excepted'  => $division->id
                    ])
                    @if($errors->has('parent_id'))
                        <span class="invalid-feedback">
                            {{ $errors->first('parent_id') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-success"
                            value="PATCH"
                            name="_method"
                            type="submit">
                        {{ __('ui.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
