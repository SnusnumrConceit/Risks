@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => __('divisions.new')])
            </div>
        </div>
        <div class="card-body col-lg-4">
            <form action="{{ route('divisions.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">
                        {{ __('divisions.name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('name')) is-invalid @endif"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
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
                    <select name="parent_id"
                            id="parent_id"
                            class="form-control @if($errors->has('parent_id')) is-invalid @endif"
                    >
                        <option value=""></option>
                        @foreach(\App\Division::all() as $division)
                            <option value="{{ $division->id }}"
                                    @if(intval(old('parent_id')) === intval($division->id)) selected @endif
                            >
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('parent_id'))
                        <span class="invalid-feedback">
                            {{ $errors->first('parent_id') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-success" type="submit">
                        {{ __('ui.add') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
