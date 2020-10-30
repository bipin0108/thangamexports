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
            <h1>Products</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Products</li>
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
              <h3 class="card-title">Products</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('product.create') }}"> <i class="fa fa-plus"></i> Create</a>
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
                  <th>Weight</th>
                  <th>Stone/Diamond</th>
                  <th>KT</th>
                  <th>Is Popular</th>
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

  <!-- Modal -->
  <div class="modal fade in" id="modalDel">
    <div class="modal-dialog" >
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Delete Confirmation</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form method="POST" action="" id="frmDel">
          @csrf
          {{ method_field('DELETE') }}
          <div class="modal-body">
            <p>Are you sure you want to delete?</p>
          </div>
          <div class="modal-footer"> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           <input type="submit" class="btn btn-primary btnclass" value="Yes Delete!">
          </div>
        </form>
      </div>
    </div>
  </div>

	<script>
		$(document).ready(function(){

      $(document).on('click', '.delete', function(){
        var id = $(this).data('id');
        var url = '{{ route("product.destroy", ":id") }}';
        url = url.replace(':id', id);
        $("#frmDel").attr('action',url);
        $("#modalDel").modal('show');
      });

		 	var table = $("#table").DataTable({
	       		processing: true,
	       		serverSide: true,
	       		ajax: {
	       			url: "{{ route('product.index') }}"
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
              { data: 'weight', name: 'weight' }, 
              { data: 'stone', name: 'stone' }, 
              { data: 'kt', name: 'kt' }, 
              { data: 'is_popular', name: 'is_popular' }, 
	       			{ data: 'action', name: 'action', oderable: false }
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

      $(document).on('click', '.is_popular', function(){
        var product_id = $(this).data('product_id');
        var is_popular = $(this).data('is_popular');
        $.post('product-is-popular',{"_token": "{{ csrf_token() }}",product_id:product_id,is_popular:is_popular},function(res){
          table.draw();
        });
      });

		});
	</script>
@endsection

