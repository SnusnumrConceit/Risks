@extends('layouts.app')

@section('content')
    <div class="row col-12">
        <div class="h2">
            @include('common.back', ['title' => __('divisions.divisions')])

            @can('create', \App\Division::class)
                <a href="{{ route('divisions.create') }}" class="btn btn-sm btn-outline-success">
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
                               placeholder="{{ __('divisions.search_string') }}"
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
                        <th scope="col">{{ __('divisions.name') }}</th>
                        <th scope="col">{{ __('divisions.parent_id') }}</th>
                        <th scope="col">{{ __('divisions.created_at') }}</th>
                        <th scope="col">{{ __('divisions.updated_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($divisions as $division)
                    <tr>
                        <td>
                            <a href="{{ route('divisions.show', ['division' => $division->uuid]) }}">
                                {{ $division->name }}
                            </a>
                        </td>
                        <td>{{ optional($division->parent)->name }}</td>
                        <td>{{ $division->created_at->format('d.m.Y H:i:s') }}</td>
                        <td>{{ $division->updated_at->format('d.m.Y H:i:s') }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @include('common.actions', ['entityName' => 'division', 'entity' => $division])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">{{ __('divisions.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $divisions->links() }}
    </div>
@endsection
