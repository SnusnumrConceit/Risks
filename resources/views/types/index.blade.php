@extends('layouts.app')

@section('content')
    <div class="row col-12 ml-2">
        <div class="h2">
            {{ __('types.types') }}

            @can('create', \App\Type::class)
                <a href="{{ route('types.create') }}" class="btn btn-sm btn-outline-success">
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
                               placeholder="{{ __('types.search_string') }}"
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
                        <th scope="col">{{ __('types.name') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($types as $type)
                    <tr>
                        <td>{{ $type->name }}</td>
                        <td>
                            <div class="row col justify-content-end">
                                @can('update', $type)
                                    <a href="{{ route('types.edit', ['type' => $type]) }}" class="btn btn-outline-primary">
                                        {{ __('ui.edit') }}
                                    </a>
                                @endcan
                                @can('delete', $type)
                                    <form method="POST" action="{{ route('types.destroy', ['type' => $type]) }}" class="ml-2">
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
                        <td colspan="2">{{ __('types.empty') }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-center">
        {{ $types->links() }}
    </div>
@endsection
