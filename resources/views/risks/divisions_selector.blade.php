<select name="{{ $name }}" id="{{ $name }}" class="form-control">
    <option value="" disabled @empty(request($name)) selected @endempty>
        {{ __('divisions.division') }}
    </option>
    @if(! isset($required) && auth()->user()->hasPermission('divisions_view'))
        <option value="">-</option>
    @endif
    @foreach($divisions as $division)
        @include('risks.divisions_hierarchy', array_merge(compact('division', 'name'), ['excepted' => isset($excepted) ? $excepted : '']))
    @endforeach
</select>
