<div class="col-md-8 offset-md-2">
	@if( session()->has('success') )
      	<div class="alert alert-success alert-dismissible fade show" role="alert">
			{{ session()->get('success') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
  	@endif
  	@if( session()->has('errors') )
      	<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <!-- @foreach((session()->get('errors')) as $errors)
			{{ errors }}
            @endforeach -->
            {{ session()->get('errors') }}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    	<span aria-hidden="true">&times;</span>
		  	</button>
		</div>
  	@endif
</div>
