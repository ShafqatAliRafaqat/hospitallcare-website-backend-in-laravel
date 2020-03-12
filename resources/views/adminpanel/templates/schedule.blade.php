
    @if($result)
        @foreach($result as $r)
        <li class="list-group-item schedule-box-li"> {{$r->day_from}}-{{$r->day_to}} From  {{$r->time_from}}-{{$r->time_to}}</li>
        @endforeach
    @endif
</ul>
