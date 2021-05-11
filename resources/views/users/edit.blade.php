@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $user->full_name])
            </div>
        </div>
        <div class="card-body col-12">
            <form action="{{ route('users.update', ['user' => $user]) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="last_name">
                        {{ __('users.last_name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('last_name')) is-invalid @endif"
                           name="last_name"
                           id="last_name"
                           value="{{ old('last_name', $user->last_name) }}"
                           required
                    >
                    @if($errors->has('last_name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('last_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="first_name">
                        {{ __('users.first_name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('first_name')) is-invalid @endif"
                           name="first_name"
                           id="first_name"
                           value="{{ old('first_name', $user->first_name) }}"
                           required
                    >
                    @if($errors->has('first_name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('first_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="middle_name">
                        {{ __('users.middle_name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('middle_name')) is-invalid @endif"
                           name="middle_name"
                           id="middle_name"
                           value="{{ old('middle_name', $user->middle_name) }}"
                           required
                    >
                    @if($errors->has('middle_name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('middle_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="appointment">
                        {{ __('users.appointment') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('appointment')) is-invalid @endif"
                           name="appointment"
                           id="appointment"
                           value="{{ old('appointment', $user->appointment) }}"
                           required
                    >
                    @if($errors->has('appointment'))
                        <span class="invalid-feedback">
                            {{ $errors->first('appointment') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="email">
                        {{ __('users.email') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('email')) is-invalid @endif"
                           name="email"
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           required
                    >
                    @if($errors->has('email'))
                        <span class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="division_id">
                        {{ __('divisions.division') }}
                    </label>
                    @include('risks.divisions_selector', [
                        'divisions' => $availableDivisions,
                        'name'      => 'division_id',
                        'selected'  => $user->division_id,
                        'required'  => true,
                    ])
                    @if($errors->has('division_id'))
                        <span class="invalid-feedback">
                            {{ $errors->first('division_id') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="is_responsible">
                        <input type="checkbox"
                               name="is_responsible"
                               id="is_responsible"
                               value="1"
                               @if($user->is_responsible) checked @endif>
                        {{ __('users.is_responsible') }}
                    </label>
                    @if($errors->has('is_responsible'))
                        <span class="invalid-feedback">
                            {{ $errors->first('is_responsible') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="role_uuid">
                        {{ __('roles.role') }}
                    </label>
                    <select name="role_uuid"
                            id="role_uuid"
                            class="form-control @if($errors->has('role_uuid')) is-invalid @endif"
                            required
                    >
                        @foreach($roles as $role)
                            <option value="{{ $role->uuid }}"
                                    @if(old('role_uuid', $user->role_uuid) === $role->uuid) selected @endif>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('role_uuid'))
                        <span class="invalid-feedback">
                            {{ $errors->first('role_uuid') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password">
                        {{ __('users.password') }}
                    </label>
                    <input type="password"
                           class="form-control @if($errors->has('password')) is-invalid @endif"
                           name="password"
                           id="password"
                           value=""
                    >
                    @if($errors->has('password'))
                        <span class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        {{ __('users.password_confirmation') }}
                    </label>
                    <input type="password"
                           class="form-control"
                           name="password_confirmation"
                           id="password_confirmation"
                           value=""
                    >
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-success"
                            value="PATCH"
                            name="_method"
                            type="submit">
                        {{ __('ui.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
