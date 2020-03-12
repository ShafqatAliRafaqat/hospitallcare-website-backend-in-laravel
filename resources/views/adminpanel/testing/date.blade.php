<html>
    <head>
    <script type="text/javascript" src="{{ url('https://cdn.jsdelivr.net/jquery/latest/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}" />
    </head>
    <body>
        <div class="container pt-5">
    <div class="form-group">
         <form class="form-horizontal" method="post" action="{{ route('exports') }}">
        @csrf
            <input type="text" name="daterange" value="01/01/2018 - 01/15/2018" />
            <div class="form-group row">
              <div class="col-md-9 ml-auto">
                <button type="submit" class="btn btn-primary">Export</button>
              </div>
            </div>
         </form>
    </div>
</div>



</body>
<script>
$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
</html>
