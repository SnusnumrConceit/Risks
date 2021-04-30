@can('update', $entity)
    <a href="{{ route(Str::plural($entityName) . '.edit', [$entityName => $entity]) }}" class="btn btn-outline-primary">
        {{ __('ui.edit') }}
    </a>
@endcan
@can('delete', $entity)
    <form method="POST" action="{{ route(Str::plural($entityName) . '.destroy', [$entityName => $entity]) }}" class="ml-2">
        @csrf

        <button class="btn btn-outline-danger" value="DELETE" name="_method">
            {{ __('ui.remove') }}
        </button>
    </form>
@endcan
