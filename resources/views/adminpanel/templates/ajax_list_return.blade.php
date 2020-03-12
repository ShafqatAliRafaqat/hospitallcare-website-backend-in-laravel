    @if($result)
        @foreach($result as $r)
        <option value="{{$r->name}}">
        @endforeach
    @endif
