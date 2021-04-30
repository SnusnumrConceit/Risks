@extends('layouts.app')

@section('content')
    <div class="row col-12">
        <div class="h2">
            @include('common.back', ['title' => __('roles.roles')])

            @can('create', \App\Role::class)
                <a href="{{ route('roles.create') }}" class="btn btn-sm btn-outline-success">
                    {{ __('ui.add') }}
                </a>
            @endcan
        </div>
    </div>

    <div class="card card-body">
        <form>
            <div class="form-group row">
                    <div class="col">
                        <input type="text"
                               class="form-control"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="{{ __('roles.search_string') }}"
                        >
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-outline-success" type="submit">
                            {{ __('ui.search') }}
                        </button>
                    </div>
            </div>
        </form>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">{{ __('roles.name') }}</th>
                        <th scope="col">{{ __('roles.created_at') }}</th>
                        <th scope="col">{{ __('roles.updated_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td>
                            <a href="{{ route('roles.show', ['role' => $role->uuid]) }}">
                                {{ $role->name }}
                            </a>
                        </td>
                        <td>{{ $role->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $role->updated_at->format('d.m.Y H:i:s') }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @include('common.actions', ['entityName' => 'role', 'entity' => $role])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">{{ __('roles.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $roles->links() }}
    </div>
@endsection
