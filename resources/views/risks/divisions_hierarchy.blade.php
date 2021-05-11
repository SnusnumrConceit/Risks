@if($loop->first && $key)
    <option value="" disabled>{{ $divisions->first()->where('id', intval($key))->first()->name }}</option>
@elseif($loop->first && ! $key)
    <option value="" disabled>{{ __('divisions.main') }}</option>
@endif
<option value="{{ $division->id }}" @if(( isset($selected) ? $selected : intval(request($name)) ) === $division->id) selected @endif>{{ $division->name }}</option>
