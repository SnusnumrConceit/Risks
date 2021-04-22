@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title ml-4 border-bottom">
            <div class="h2">{{ $type->name }}</div>
        </div>
        <div class="card-body col-lg-4">
            <form action="{{ route('types.update', ['type' => $type]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">
                        {{ __('types.name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('name')) is-invalid @endif"
                           name="name"
                           id="name"
                           value="{{ old('name', $type->name) }}"
                           required
                    >
                    @if($errors->has('name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('name') }}
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
