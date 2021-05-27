@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title border-bottom">
            <div class="h2">
                @include('common.back', ['title' => $role->name])
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update', ['role' => $role]) }}" method="POST" class="row">
                @csrf

                <div class="form-group col-lg-4">
                    <label for="name">
                        {{ __('roles.name') }}
                    </label>
                    <input type="text"
                           class="form-control @if($errors->has('name')) is-invalid @endif"
                           name="name"
                           id="name"
                           value="{{ old('name', $role->name) }}"
                           required
                    >
                    @if($errors->has('name'))
                        <span class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </span>
                    @endif

                    <div class="form-group mt-4">
                        <button class="btn btn-outline-success" type="submit" value="PATCH" name="_method">
                            {{ __('ui.edit') }}
                        </button>
                    </div>
                </div>

                <div class="form-group col-lg-8 row">
                    <legend class="border-bottom ml-lg-3">
                        {{ __('permissions.permissions') }}
                    </legend>
                    @foreach($permissionGroups as $group => $permissions)
                        @empty($group) @continue @endempty

                        <div class="permissions-sections col-md-6">
                            <legend>{{ __('permissions.' . $group) }}</legend>

                            @foreach($permissions as $permission)
                                <div class="form-group">
                                    <label for="permission-{{ $permission->id }}">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               id="permission-{{ $permission->id }}"
                                               value="{{ $permission->id }}"
                                               @if($role->permissions->contains('id', $permission->id) || in_array($permission->id, old('permissions', []))) checked @endif
                                        >
                                        {{ __('permissions.' . $permission->name) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
@endsection
