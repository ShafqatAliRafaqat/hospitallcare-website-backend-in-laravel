@extends('adminpanel.layout')
@section('title', 'Doctor FAQs | HospitALL')
@section('content')
<?php
use Illuminate\Support\Facades\DB;
?>
<div class="container-fluid px-xl-5">
    <section class="py-5">
      <div class="row">
        @include('adminpanel.notification')
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h6 class="text-uppercase mb-0">Doctors <buton data-toggle="modal" data-target="#faqModal"class="btn btn-sm btn-dark float-right">Create New</buton></h6>
              </div>
              <div class="card-body table-responsive">
              <div class="mb-2"> -
                <a class="toggle-vis clr-green" data-column="1">Question</a> -
                <a class="toggle-vis clr-green" data-column="2">Answer</a> -
                <a class="toggle-vis clr-red"   data-column="3">Created by</a> -
                <a class="toggle-vis clr-green" data-column="4">Updated by</a> -
                <a class="toggle-vis clr-green" data-column="5">Status</a> -
                <a class="toggle-vis clr-green" data-column="6">Action</a> -
              </div>
                <table class="table table-striped table-sm card-text" id="centers">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Question</th>
                      <th>Answer</th>
                      <th>Created by</th>
                      <th>Updated by</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($faqs)
                      @php $no = 1; @endphp
                      {{-- @dd($faqs); --}}
                      @foreach($faqs as $d)
                        <tr id="doctor_id_{{$d->id}}">
                          <th scope="row">{{$no++}}</th>

                          <td>{{ $d->question }}</td>
                          <td>{!! $d->answer !!}</td>
                          @php
                            $created_by = Auth::user()->find($d->created_by);
                            $updated_by = Auth::user()->find($d->updated_by);
                          @endphp
                          <td>{{ isset($created_by->name)? $created_by->name:"" }}</a></td>
                          <td>{{ isset($updated_by->name)? $updated_by->name:"" }}</a></td>
                          <td><?php echo $status = ($d->is_active == 1 ? 'Active' : 'Not Active'); ?></td>
                          <td class="text-center" >
                            <div style="display: flex">

                              <a href="{{ route('faqs_edit', $d->id) }}" title="Edit FAQ"><i class="fa fa-edit mr-2"></i></a>
                                <!-- <td class="text-center"> -->
                                  <a class="delete" data-id="{{ $d->id }}" href="#" title="Delete Doctor"><i class="fa fa-trash " style="padding-left:10; color:red;"></i></a>
                                  <form id="deleteForm{{$d->id}}" method="post" action="{{ route('delete_faqs', $d->id) }}">
                                      @csrf @method('delete')
                                  </form>
                              </div>

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
          <div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="faqModal">Add FAQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form method="post" action="{{ route('store_faqs') }}">
                 @csrf @method('post')
                 <input type="hidden" name="doctor_id" value="{{ $doctor_id }}">
                <div class="modal-body">
                <div class="container-fluid">
                    <div id="dynamic_field_faqs">
                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Add Question</label>
                          <div class="col-md-8">
                          <input type="text" placeholder="Question" class="form-control" required name="question" />
                            @if($errors->has('question'))
                              <div class="invalid-feedback ml-3">{{ $errors->first('question') }}</div>
                            @endif
                          </div>
                        <label class="col-md-1 form-control-label">Active</label>
                        <div class="col-md-1">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active" id="exampleRadios1" value="1" checked>
                          <label class="form-check-label" for="exampleRadios1">
                            Yes
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="is_active" id="exampleRadios2" value="0">
                          <label class="form-check-label" for="exampleRadios2">
                            No
                          </label>
                        </div>
                        </div>
                        </div>

                      <div class="form-group row">
                        <label class="col-md-2 form-control-label">Add Answer</label>
                          <div class="col-md-10">
                          <textarea class="form-control tiny" name="answer" id="text" cols="30" rows="10">{{ old('answer') }}</textarea>
                          @if($errors->has('answer'))
                          <div class="invalid-feedback ml-3">{{ $errors->first('answer') }}</div>
                          @endif
                          </div>
{{--                           <div class="col-md-2 form-control-label text-center">
                            <button type="button" name="add_allergies_notes" id="add_allergies_notes" class="btn btn-success">Add More</button>
                          </div> --}}
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
<script src="{{ asset('backend/js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/js/tinymce-config.js') }}" ></script>
<script src="{{ asset('backend/js/sweetalert/sweetalert.js') }}"></script>
{{-- <script>

// dynamic input fields for Allergies Notes
$(document).ready(function(){
  var i=0;
  $('#add_allergies_notes').click(function(){
    i++;
    var html = '';
    html += '<div id="faqs'+i+'">';
    html += '<hr>';
    html += '<div class="form-group row">';
    html += '<label class="col-md-2 form-control-label">Add Question</label>';
    html += '<div class="col-md-8">';
    html += '<input type="text" placeholder="Question" class="form-control" name="question[]" id="" required/>';
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
    html += '<label class="col-md-2 form-control-label">Add Answer</label>';
    html += '<div class="col-md-8">';
    html += '<textarea class="form-control" name="answer[]" cols="30" id="text'+i+'" rows="10">{{ old('answer') }}</textarea>';
    html += '</div>';
    html += '<div class="col-md-2 form-control-label text-center"> <button type="button" name="remove" id="faqs'+i+'" class="btn btn-danger btn_remove">X</button></div>';
    html += '</div>';
    html += '</div>';

    $('#dynamic_field_faqs').append(html);
    $('#text'+i+'').addClass('tiny');
  });

  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id");
    $('#'+button_id+'').remove();
  });

});
// end of dynamic fields for Allergies Notes
</script> --}}
<script>
$(document).on('click', '.delete', function(){
    var id = $(this).data('id');
    console.log(id);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    swal({
        title: "Are you sure?",
        text: "You want to delete doctor!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true
        },
        function (isConfirm) {
        if (!isConfirm) return;
          $.ajax({

            type: "DELETE",
            url:"{{ route('delete_faqs','id') }}",
            data: { id : id},
            success: function () {
              setTimeout(function () {
                  swal("Deleted!", "FAQ has been deleted.", "success");
                }, 2000);
                $("#doctor_id_" + id).remove();
            },
        })
        }
        );
});
</script>
<script>
$(document).ready(function() {
    var table = $('#centers').DataTable( {} );
    //Removing some Columns on Load
    table.columns( [3] ).visible( false );

    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
        if(column.visible() == true){
          $(this).addClass('clr-red');
          $(this).removeClass('clr-green');
        } else{
          $(this).removeClass('clr-red');
          $(this).addClass('clr-green');
        }
        // Toggle the visibility
        column.visible( ! column.visible() );
    });
    setTimeout(function() {
      $('.alert').fadeOut('slow');
  }, 2000);
});
</script>
@endsection
