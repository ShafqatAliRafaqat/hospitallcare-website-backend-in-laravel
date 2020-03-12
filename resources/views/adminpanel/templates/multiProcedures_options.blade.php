@if ( count($result) > 0)
    @foreach($result as $r)
    <option value="{{ $r->id }}" selected>{{ $r->name }}</option>
    @endforeach
@else
    <option value="">This Specialization has no treatments</option>
@endif
