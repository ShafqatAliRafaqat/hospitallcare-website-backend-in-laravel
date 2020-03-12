@if ( count($result) > 0)
@if($data == "Procedure")
    <option value="">Select Procedure</option>
    @foreach($result as $r)
    <option value="{{ $r->id }}">{{ $r->treatment_name }}</option>
    @endforeach
@endif
@else
<option value="">There is no treatment in this center</option>
@endif