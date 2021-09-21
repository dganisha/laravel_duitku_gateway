@extends('template')

@section('content')
	<div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Login Untuk Akses</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form">
            	@csrf
              	<div class="box-body">
								@if(session()->has('message'))
								<div class="alert alert-success">{{ Session::get('message') }}</div>
								@endif

	                <div class="form-group">
	                  <label>Nomor Transaksi</label>
	                  <input type="text" required="" value="{{ $orders['reff_number_to_duitku'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Harga</label>
	                  <input type="text" required="" value="{{ $orders['amount'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Status Pembayaran</label>
	                  <input type="text" required="" value="{{ $orders['status'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Metode Pembayaran</label>
	                  <input type="text" required="" value="{{ $orders['payment_method_name'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Nomor Virtual Account <small>*jika menggunakan metode pembayaran VA</small></label>
	                  <input type="text" required="" value="{{ $orders['virtual_account_number'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Link Pembayaran : </label>
	                  <a target="_blank" href="{{ $orders['duitku_payment_url'] }}">{{ $orders['duitku_payment_url'] }}</a>
	                  <!-- <input type="text" required="" value="{{ $orders['product'] }}" class="form-control" disabled=""> -->
	                </div>
	                <div class="form-group">
	                  <label>Produk </label>
	                  <input type="text" required="" value="{{ $orders['product'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Deskripsi Produk</label>
	                  <input type="text" required="" value="{{ $orders['product_description'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Data Email</label>
	                  <input type="text" required="" value="{{ $orders['customer_email'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Data Nama</label>
	                  <input type="text" required="" value="{{ $orders['customer_name'] }}" class="form-control" disabled="">
	                </div>
	                <div class="form-group">
	                  <label>Data Nomor HP</label>
	                  <input type="text" required="" value="{{ $orders['customer_phone'] }}" class="form-control" disabled="">
	                </div>

	                @if($orders['status'] == "Pending")
	                <div class="form-group">
	                  <a href="/check_payment?trxid={{ $orders['reff_number_to_duitku'] }}" type="button" class="btn btn-primary">Cek Pembayaran</a>
	                </div>
	                @endif
              	</div>
              <!-- /.box-body -->


              <div class="box-footer">
                <a href="/my_transaction" type="button" class="btn btn-primary">Kembali</a>
              </div>
            </form>
          </div>
        </div>
    </div>
@endsection