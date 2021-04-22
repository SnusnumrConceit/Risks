@extends('layouts.app')

@section('content')
    <div class="card p-4">
        <div class="card-title ml-4 border-bottom">
            <div class="h2">{{ $role->name }}</div>
        </div>
        <div class="card-body row">
            <div class="form-group col-lg-4">
                <legend>{{ __('roles.name') }}</legend>
                <span>{{ $role->name }}</span>
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
                                    @if($role->permissions->contains('id', $permission->id))
                                        <strong class="text-success">+</strong>
                                    @else
                                        <strong class="text-danger">-</strong>
                                    @endif
                                        {{ __('permissions.' . $permission->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
