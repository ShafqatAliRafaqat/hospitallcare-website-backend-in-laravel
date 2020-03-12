@if ( count($result) > 0)
    <option value="">Select {{$data}}</option>
    @foreach($result as $r)
    <option value="{{ $r->id }}">{{ $r->center_name }}</option>
    @endforeach
@else
    <option value="">No {{$data}}s in this City </option>
@endif
