@extends('doctorpanel.layout')
@section('title', 'Schedule | HospitALL')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('centerpanel.notification')
            @if($center_id[0]!= null)
            @if($doctorschedule)
            @php $i=0;  @endphp
              @foreach($doctorschedule as $schedule)
              @php count($schedule);

              @endphp
              @if($schedule != null)
            <div class="col-lg-4 mb-5">
              <div class="card">
                <div class="card-header">
                    <h6 class="text-uppercase mb-0">Center {{$i+1}}<a href="{{ route('doctorschedule.edit', $centers[$i]->id) }}" class="float-right">
                      <img src="{{ asset('backend/web_imgs/edit2.png') }}" width="22px">
                    </a>
                  </h6>
                </div>
                <div class="card-body ">
                  <h5 class="card-title" style="margin-bottom: 5px;">{{ $centers[$i]->center_name }}</h5>
                  @if($doctorschedule[$i])
                    @if($doctorschedule[$i][0]->is_primary == 1)
                    <div class="span-primary text-right">
                    <span>Primary</span>
                    </div>
                    @endif
                  @endif
                  @foreach($doctorschedule[$i] as $cs1)
                  <p>{{$cs1->day_from}}-{{$cs1->day_to}} From {{$cs1->time_from}}-{{$cs1->time_to}}</p>
                  @endforeach

                </div>
              </div>
            </div>
              @endif
              @php $i++; @endphp
            @endforeach
          @endif
          @else
        <div class="col-md-12" style="text-align: center;"><h3> Select Center First </h3> </div>
        @endif
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#customers').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});

$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    if ( confirm('Are you sure to delete?') ){
      $('#deleteForm'+id).submit();
    }else {

    }
});
</script>
@endsection
