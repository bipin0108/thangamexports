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
            <h1>Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Orders</li>
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
              <h3 class="card-title">Orders</h3>
              <div class="text-right">
                
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Order No</th>
                  <th>Username</th>
                  <th>Mobile</th> 
                  <th>Email</th>  
                  <th>Status</th>
                  <th>Action</th> 
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

		 	var table = $("#table").DataTable({
	       		processing: true,
	       		serverSide: true,
	       		ajax: {
	       			url: "{{ route('order.index') }}"
		       	},
		       	columns: [
              { data: 'DT_RowIndex', name: 'DT_RowIndex' },
              { data: 'order_no', name: 'order_no' },  
              { data: 'username', name: 'username' },  
              { data: 'mobile', name: 'mobile' }, 
              { data: 'email', name: 'email' }, 
              { data: 'status', name: 'status' },  
	       			{ data: 'action', name: 'action', oderable: false }
		       	]
		    });

        $(document).on('change', '.status', function(){
          var order_id = $(this).find(':selected').data('order_id');
          var status = $(this).val();
          console.log(order_id);
          console.log(status);
          $.post("{{ route('order.status_change') }}",{"_token": "{{ csrf_token() }}",order_id:order_id,status:status},function(res){
            table.draw();
          });
        });

		});
	</script>
@endsection

