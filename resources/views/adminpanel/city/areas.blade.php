@extends('adminpanel.layout')
@section('title', 'Areas of '.$city->name)
@section('content')
<div class="container-fluid px-xl-5">
    <section class="py-5">
        <div class="row">
            @include('adminpanel.notification')
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-header"><h6 class="text-uppercase mb-0">List of Areas in {{$city->name}} <buton data-toggle="modal" data-target="#areaModal"class="btn btn-sm btn-dark float-right">Create New</buton></h6>
                  </div>
                  <div class="card-body table-responsive">
                    <table class="table table-striped table-sm card-text" id="status">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Area Name</th>
                          <th>Slug</th>
                          <th>Status</th>
                          <th>Edit</th>
                          <th>Delete</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if(count($areas) > 0 )
                            @php $no=1 @endphp
                            @foreach($areas as $a)
                            <tr>
                              <th scope="row">{{$no++}}</th>
                              <td>{{ $a->name }}</td>
                              <td>{{ $a->slug }}</td>
                              <td>{{ $a->is_active == 1 ? 'Active' : 'Not Active' }}</td>
                              <td><a href="{{ route('city.show', $a->id) }}"><i class="fa fa-edit"></i></a></td>
                              <td>
                                <a class="delete" data-id="{{ $a->id }}" href="#"><i class="fa fa-trash"></i></a>
                                <form id="deleteForm{{$a->id}}" method="post" action="{{ route('city.destroy', $a->id) }}">
                                    @csrf @method('delete')
                                </form>
                              </td>
                            </tr>
                            @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
          <!-- Modal to add new allergies notes  -->
          <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-labelledby="areaModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="areaModal">Add Areas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="{{ route('store_area') }}">
                 @csrf @method('post')
                 <input type="hidden" name="city_id" value="{{ $city->id }}">
                <div class="modal-body">
                <div class="container-fluid">
                    <div id="dynamic_field_area">
                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Add Area</label>
                          <div class="col-md-8">
                          <input type="text" placeholder="Area" class="form-control" required name="area[]" />
                            @if($errors->has('area'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('area') }}</div>
                            @endif
                          </div>
                        <label class="col-md-1 form-control-label">Active</label>
                        <div class="col-md-1">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active[]" id="exampleRadios1" value="1" checked>
                          <label class="form-check-label" for="exampleRadios1">
                            Yes
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active[]" id="exampleRadios2" value="0">
                          <label class="form-check-label" for="exampleRadios2">
                            No
                          </label>
                        </div>
                        </div>
                        </div>

                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Add Slug</label>
                          <div class="col-md-8">
                          <input type="text" placeholder="slug" class="form-control" required name="slug[]" />
                          @if($errors->has('slug'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('slug') }}</div>
                          @endif
                          </div>
                          <div class="col-md-2 form-control-label text-center">
                            <button type="button" name="add_area" id="add_area" class="btn btn-success">Add More</button>
                          </div>
                      </div>
                      </div>
                </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- End of Modal to add new Allergies notes  -->
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
<script>
// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_area').click(function(){
    i++;
    var html = '';
    html += '<div id="areas'+i+'">';
    html += '<hr>';
    html += '<div class="form-group row">';
    html += '<label class="col-md-2 form-control-label">Add Area</label>';
    html += '<div class="col-md-8">';
    html += '<input type="text" placeholder="Area" class="form-control" name="area[]" id="" required/>';
    html += '</div>';
    html += '<label class="col-md-1 form-control-label">Active</label>';
    html += '<div class="col-md-1">';

    html += '<div class="form-check"> ';
    html += '<input class="form-check-input" type="radio" name="is_active['+i+']" id="exampleRadios1" value="1" checked>';
    html += '<label class="form-check-label" for="exampleRadios1">Yes</label>';
    html += '</div>';
    html += '<div class="form-check"> ';
    html += ' <input class="form-check-input" type="radio" name="is_active['+i+']" id="exampleRadios2" value="0">';
    html += '<label class="form-check-label" for="exampleRadios2">No</label>';
    html += '</div>';
    html += '</div>';

    html += '</div>';
    html += '<div class="form-group row">';
    html += '<label class="col-md-2 form-control-label">Add Slug</label>';
    html += '<div class="col-md-8">';

    html += '<input type="text" placeholder="Slug" class="form-control" required name="slug[]" />';
    html += '</div>';
    html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="areas'+i+'" class="btn btn-danger btn_remove">X</button></div>';
    html += '</div>';
    html += '</div>';

    $('#dynamic_field_area').append(html);
    // $('#text'+i+'').addClass('tiny');
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });

});
// end of dynamic fields for Allergies Notes
</script>
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    swal({
        title: "Are you sure?",
        text: "You want to delete this Area?",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function(){
            setTimeout(function () {
        $('#deleteForm'+id).submit();
        swal("Deleted!", "Area has been deleted.", "success");
    }, 2000);
        });
});
</script>
<script>
$(document).ready(function() {
    $('#status').DataTable();
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 2000);
});
</script>
@endsection
