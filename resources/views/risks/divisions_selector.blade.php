<select name="{{ $name }}" id="{{ $name }}" class="form-control">
    <option value="" disabled @empty(request($name)) selected @endempty>
        {{ __('divisions.division') }}
    </option>
    @if(! isset($required))
        <option value=""></option>
    @endif
    @foreach($divisions as $division)
        <option value="{{ $division->id }}"
                @if(( isset($selected) ? $selected : intval(request($name)) ) === $division->id) selected @endif>
            {{ $division->name }}
        </option>
        @foreach($division->children as $child)
            @include('risks.divisions_hierarchy', ['division' => $child, 'name' => $name])
        @endforeach
    @endforeach
</select>
