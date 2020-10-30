@extends('admin.template')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @include('flash-message')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order Detail ({{ $order->order_no }})</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
              <li class="breadcrumb-item active">Order Detail ({{ $order->order_no }})</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Order Detail ({{ $order->order_no }})</h3>
              <div class="text-right">
                
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Image</th>
                  <th>Code</th> 
                  <th>Qty</th>
                  <th>Note</th> 
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

	<script>
		$(document).ready(function(){
      var url = "{{ route('order.detail', ':id') }}";
      url = url.replace(':id', {{ $order->order_id }});

 	      var table = $("#table").DataTable({
       		processing: true,
       		serverSide: true,
       		ajax: {
       			url: url
	       	},
	       	columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            {
                "name": "image",
                "data": "image",
                "render": function (data, type, full, meta) {
                    if(data == ''){
                      return 'No Image'; 
                    }
                     
                    return "<a data-magnify=\"gallery\" href=\"{{ Storage::disk('s3')->url('') }}"+ data + "\"> <img class=\"img-responsive\" height=\"50\"/ src=\"{{ Storage::disk('s3')->url('') }}"+ data + "\"></a>";
                },
                "title": "Image",
                "orderable": true,
                "searchable": true
            }, 
            { data: 'product_code', name: 'product_code' },  
            { data: 'qty', name: 'qty' }, 
            { data: 'note', name: 'note' },
	       	],
          "drawCallback": function( settings ) {
            $('[data-toggle="tooltip"]').tooltip();
            $('[data-magnify]').magnify({
              resizable: false,
              headToolbar: [
                'close'
              ],
              initMaximized: true
            });
          }
		    });

 

		});
	</script>
@endsection

