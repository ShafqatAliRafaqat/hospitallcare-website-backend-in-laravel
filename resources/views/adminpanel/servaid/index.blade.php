@extends('adminpanel.layout')
@section('title', 'Servaid')
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h6 class="text-uppercase mb-0">Servaid</h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="orders">
                        <thead>
                            <tr>
                              <th>#</th>
                              <th>User Name</th>
                              <th>Phone</th>
                              <th>Address</th>
                              <th>City</th>
                              <th>View Items</th>
                              <th>Status</th>
                              <th>Comments On Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($servaid_orders)
                                @php $no=1 @endphp
                                @foreach($servaid_orders as $orders)
                                <tr>
                                  <th scope="row">{{$no++}}</th>
                                  <td>{{ $orders->name }}</td>
                                  <td>{{ $orders->phone }}</td>
                                  <td>{{ $orders->address }}</td>
                                  <td>{{ $orders->city }}</td>
                                  <td>{{ $orders->items }}</td>
                                  <td>{{ $order->status }}</td>
                                  <td>Add Order Status</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#orders').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
