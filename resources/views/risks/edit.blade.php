@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $risk->name])
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('risks.update', ['risk' => $risk->uuid]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">
                        {{ __('risks.name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('name')) is-invalid @endif"
                           name="name"
                           id="name"
                           value="{{ old('name', $risk->name) }}"
                           required
                    >
                    @if($errors->has('name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="description">
                        {{ __('risks.description') }}
                    </label>
                    <textarea name="description"
                              id="description"
                              cols="30"
                              rows="10"
                              class="form-control @if($errors->has('description')) is-invalid @endif"
                    >{{ old('description', $risk->description) }}</textarea>
                    @if($errors->has('description'))
                        <span class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="expired_at">
                        {{ __('risks.expired_at') }}
                    </label>
                    <input type="date"
                           name="expired_at"
                           value="{{ old('expired_at', $risk->expired_at->toDateString()) }}"
                           class="form-control @if($errors->has('expired_at')) is-invalid @endif"
                           placeholder="{{ __('risks.expired_at') }}"
                    >
                    @if($errors->has('expired_at'))
                        <span class="invalid-feedback">
                            {{ $errors->first('expired_at') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="likelihood">
                        {{ __('risks.likelihood') }}
                    </label>
                    <select class="form-control @if($errors->has('likelihood')) is-invalid @endif"
                            name="likelihood"
                            id="likelihood"
                    >
                        @for($likelihood = 1; $likelihood <= 5; $likelihood++)
                            <option value="{{ $likelihood }}"
                                    @if(intval($likelihood) === intval(old('likelihood', $risk->likelihood))) selected @endif
                            >
                                {{ $likelihood }}
                            </option>
                        @endfor
                    </select>
                    @if($errors->has('likelihood'))
                        <span class="invalid-feedback">
                            {{ $errors->first('likelihood') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="impact">
                        {{ __('risks.impact') }}
                    </label>
                    <select class="form-control @if($errors->has('impact')) is-invalid @endif"
                            name="impact"
                            id="impact"
                    >
                        @for($impact = 1; $impact <=5; $impact++)
                            <option value="{{ $impact }}"
                                    @if(intval($impact) === intval(old('impact', $risk->impact))) selected @endif
                            >
                                {{ $impact }}
                            </option>
                        @endfor
                    </select>
                    @if($errors->has('impact'))
                        <span class="invalid-feedback">
                            {{ $errors->first('impact') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="level">
                        {{ __('risks.level') }}
                    </label>
                    <select name="level"
                            id="level"
                            class="form-control @if($errors->has('level')) is-invalid @endif"
                    >
                        @foreach(\App\Risk::getLevels() as $level)
                            <option value="{{ $level }}"
                                    @if(old('level', $risk->level) === $level) selected @endif
                            >
                                {{ __('risks.levels.' . $level) }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('level'))
                        <span class="invalid-feedback">
                            {{ $errors->first('level') }}
                        </span>
                    @endif
                </div>

                <legend class="border-bottom">{{ __('types.types') }}</legend>
                <div class="form-group">
                    @foreach(\App\Type::orderBy('name')->get() as $key => $type)
                        <div class="row col">
                            <label for="type-{{ $type->id }}">
                                <input type="checkbox"
                                       name="types[]"
                                       id="type-{{ $type->id }}"
                                       value="{{ old('types.' . $key, $type->id) }}"
                                       @if($risk->types->contains('id', $type->id)) checked @endif>
                                {{ $type->name }}
                            </label>
                        </div>
                    @endforeach
                </div>

                <legend class="border-bottom">{{ __('factors.factors') }}</legend>
                <div class="form-group">
                    @foreach($factors as $key => $factor)
                        <div class="row col">
                            <label for="factor-{{ $factor->id }}">
                                <input type="checkbox"
                                       name="factors[]"
                                       id="factor-{{ $factor->id }}"
                                       value="{{ old('factors.' . $key, $factor->id) }}"
                                       @if($risk->factors->contains('id', $factor->id)) checked @endif
                                >
                                {{ $factor->name }}
                            </label>
                        </div>
                        @forelse($factor->children as $child)
                            <div class="row col">
                                <label for="factor-{{ $child->id }}">
                                    -
                                    <input type="checkbox"
                                           name="factors[]"
                                           id="factor-{{ $child->id }}"
                                           value="{{ old('factors.' . $key, $child->id) }}"
                                           @if($risk->factors->contains('id', $child->id)) checked @endif
                                    >
                                    {{ $child->name }}
                                </label>
                            </div>
                        @empty
                        @endforelse
                    @endforeach
                </div>

                <legend class="border-bottom">{{ __('divisions.division') }}</legend>
                <div class="form-group">
                    <div class="row col">
                        <select name="division_id" id="" class="form-control @if($errors->has('division_id')) is-invalid @endif">
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                        @if($risk->division_id === $division->id) selected @endif>
                                    {{ $division->name }}
                                </option>
                                @forelse($division->children as $child)
                                    {{-- TODO вложенность только одного уровня --}}
                                    <option value="{{ $child->id }}"
                                            @if($risk->division_id === $child->id) selected @endif>
                                        {{ str_repeat('-', $child->level) . ' ' . $child->name }}
                                    </option>
                                @empty
                                @endforelse
                            @endforeach
                        </select>
                        @if($errors->has('division_id'))
                            <span class="invalid-feedback">
                                {{ $errors->first('division_id') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">
                        {{ __('risks.status') }}
                    </label>
                    <select name="status" id="status" class="form-control @if($errors->has('status')) is-invalid @endif">
                        @foreach(\App\Risk::getStatuses() as $status)
                            <option value="{{ $status }}"
                                    @if(old('status', $risk->status) === $status) selected @endif
                            >
                                {{ __('risks.statuses.' . $status) }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('status'))
                        <span class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-success" type="submit" value="PATCH" name="_method">
                        {{ __('ui.edit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
