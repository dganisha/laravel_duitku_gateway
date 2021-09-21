@extends('template')

@section('content')
	<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Transaksi Saya</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Nomor Transaksi</th>
                  <th>Harga</th>
                  <th>Metode Pembayaran</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @php $no = 1; @endphp
                @foreach($orders as $val)
                <tr>
                  <td>{{ $no++ }}</td>
                  <td>{{ $val->reff_number_to_duitku }}</td>
                  <td>{{ $val->amount }}</td>
                  <td>{{ $val->payment_method_name }}</td>
                  <td>{{ $val->status }}</td>
                  <td><a href="/admin_transaction?trxid={{ $val->reff_number_to_duitku }}">Lihat Selengkapnya</a></td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
@endsection