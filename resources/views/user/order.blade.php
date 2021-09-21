@extends('template')

@section('content')
	<div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Order via Duitku</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" role="form" action="{{ route('order.create') }}">
            	@csrf
              	<div class="box-body">
              		@if(session()->has('message'))
									<div class="alert alert-danger">{{ Session::get('message') }}</div>
									@endif

	                <div class="form-group">
	                  <label>Email User</label>
	                  <input type="email" required="" name="cust_email" value="{{ Auth::user()->email }}" class="form-control" placeholder="Enter email">
	                </div>
	                <div class="form-group">
	                  <label>Full Name User</label>
	                  <input type="text" required="" name="cust_name" value="{{ Auth::user()->name }}" class="form-control" placeholder="Enter Full Name">
	                </div>
	                <div class="form-group">
	                  <label>Phone User</label>
	                  <input type="number" required="" name="cust_phone" class="form-control" placeholder="Enter Phone">
	                </div>
	                <div class="form-group">
	                  <label>Payment Method</label>
	                  <select name="order_payment_method" required="" class="form-control">
	                  	<option disabled="" selected="">-- Pilih Metode Pembayaran --</option>
	                  	@isset($payment_method->responseCode)
	                  	@foreach($payment_method->paymentFee as $val)
	                  		<option value="{{ $val->paymentMethod . '-' . $val->paymentName }}">{{ $val->paymentName }}</option>
	                  	@endforeach
	                  	@endisset
	                  </select>
	                  <!-- <input type="text" required="" name="order_payment_method" class="form-control" placeholder="Enter Method"> -->
	                </div>
	                <div class="form-group">
	                  <label>Product</label>
	                  <input type="text" required="" name="order_product" class="form-control" placeholder="Enter Product Name">
	                </div>
	                <div class="form-group">
	                  <label>Product Description</label>
	                  <textarea name="order_product_desc" class="form-control" required="">Deskripsi produk</textarea>
	                </div>
	                <div class="form-group">
	                  <label>Product Price</label>
	                  <input type="number" required="" name="order_product_price" class="form-control" placeholder="Enter Product Price">
	                </div>
              	</div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
    </div>
@endsection