@extends('template')

@section('content')
	<div class="row">
        <!-- left column -->
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Daftar Akun</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" role="form" action="{{ route('auth.register') }}">
            	@csrf
              	<div class="box-body">
              		@if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif

					@if(session()->has('error'))
					<div class="alert alert-danger">{{ Session::get('error') }}</div>
					@endif

	                <div class="form-group">
	                  <label>Email</label>
	                  <input type="email" required="" name="email" class="form-control" placeholder="Enter email">
	                </div>
	                <div class="form-group">
	                  <label>Name</label>
	                  <input type="text" required="" name="name" class="form-control" placeholder="Enter Name">
	                </div>
	                <div class="form-group">
	                  <label>Password</label>
	                  <input type="password" required="" name="password" class="form-control" placeholder="Enter Password">
	                </div>
	                <div class="form-group">
	                  <label>Confirmation Password</label>
	                  <input type="password" required="" name="password_confirmation" class="form-control" placeholder="Enter Confirmation Password">
	                </div>
              	</div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Daftar</button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-4"></div>
    </div>
@endsection