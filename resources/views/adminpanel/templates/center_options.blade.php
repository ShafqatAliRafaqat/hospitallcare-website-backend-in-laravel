@if ( count($result) > 0)
    <option value="">Select {{$data}}</option>
    @foreach($result as $key => $value)
    <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
@else
    <option value="0">There is no {{$data}} doing this procedure</option>
@endif
