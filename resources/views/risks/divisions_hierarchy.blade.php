<option value="{{ $division->id }}"
        @if(( isset($selected) ? $selected : intval(request($name)) ) === $division->id) selected @endif>
    {{ str_repeat('-', $division->level) }} {{ $division->name }}
</option>
@foreach($division->children as $child)
    @include('risks.divisions_hierarchy', ['division' => $child, 'name' => $name])
@endforeach
