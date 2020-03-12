@if ( count($result) > 0)
    <option value="">Select {{$data}}</option>
    @foreach($result as $r)
    <option value="{{ $r->id }}">{{ $r->name }}</option>
    @endforeach
@else
    <option value="">No {{$data}} is doing this Diagnostic</option>
@endif
