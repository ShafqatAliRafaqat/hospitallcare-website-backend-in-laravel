@if ( count($result) > 0)
@if($data != "Center")
    <option value="0">Consultation</option>
@endif
@if($data == "Center")
    <option value="">Select Center</option>
    <option value="1">Future</option>
@endif
    @foreach($result as $r)
    <option value="{{ $r->id }}">{{ $r->name }}</option>
    @endforeach
@else
@if($data != "Center")
<option value="0">Consultation</option>
@endif
    <option value="">There is no {{$data}} doing this procedure</option>
@endif
