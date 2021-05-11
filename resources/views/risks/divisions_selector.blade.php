<select name="{{ $name }}" id="{{ $name }}" class="form-control">
    <option value="" disabled @empty(request($name)) selected @endempty>
        {{ __('divisions.divisions') }}
    </option>
    @foreach($divisions as $key => $divisionGroup)
        @foreach($divisionGroup as $division)
            @include('risks.divisions_hierarchy', compact('divisions', 'key', 'division', 'name'))
        @endforeach
        @if(! $key)
            <option value="" disabled>{{ __('divisions.children') }}</option>
        @endif
    @endforeach
</select>
