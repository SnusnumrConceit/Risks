@extends('layouts.app')

@section('content')
    <div class="row col-12 ml-2">
        <div class="h2">
            {{ __('users.users') }}

            @can('create', \App\User::class)
                <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-success">
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
                               placeholder="{{ __('users.search_string') }}"
                        >
                    </div>
                    <div class="col-auto">
                        <select name="role" id="role" class="form-control">
                            <option value="">{{ __('roles.role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->uuid }}"
                                        @if(request('role') === $role->uuid) selected @endif
                                >
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
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
                        <th scope="col">{{ __('users.full_name') }}</th>
                        <th scope="col">{{ __('users.email') }}</th>
                        <th scope="col">{{ __('users.appointment') }}</th>
                        <th scope="col">{{ __('roles.role') }}</th>
                        <th scope="col">{{ __('users.created_at') }}</th>
                        <th scope="col">{{ __('users.updated_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <a href="{{ route('users.show', ['user' => $user->uuid]) }}">
                                {{ $user->full_name }}
                            </a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->appointment }}</td>
                        <td>{{ optional($user->role)->name }}</td>
                        <td>{{ $user->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $user->updated_at->format('d.m.Y H:i:s') }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @can('update', $user)
                                    <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-outline-primary">
                                        {{ __('ui.edit') }}
                                    </a>
                                @endcan
                                @can('delete', $user)
                                    <form method="POST" action="{{ route('users.destroy', ['user' => $user]) }}" class="ml-2">
                                        @csrf

                                        <button class="btn btn-outline-danger" value="DELETE" name="_method">
                                            {{ __('ui.remove') }}
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">{{ __('users.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $users->links() }}
    </div>
@endsection
