@extends('layouts.app')

@section('content')
    <div class="row col-12">
        <div class="h2">
            @include('common.back', ['title' => __('factors.factors')])

            @can('create', \App\Factor::class)
                <a href="{{ route('factors.create') }}" class="btn btn-sm btn-outline-success">
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
                               placeholder="{{ __('factors.search_string') }}"
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
                        <th scope="col">{{ __('factors.name') }}</th>
                        <th scope="col">{{ __('factors.parent_id') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($factors as $factor)
                    <tr>
                        <td>{{ $factor->name }}</td>
                        <td>{{ optional($factor->parent)->name }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @include('common.actions', ['entityName' => 'factor', 'entity' => $factor])
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">{{ __('factors.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $factors->links() }}
    </div>
@endsection
