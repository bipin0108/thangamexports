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
            <h1>Sub Categories ({{ $category->name }})</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('category.index') }}">Categories</a></li>
              <li class="breadcrumb-item active">Sub Categories ({{ $category->name }})</li>
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
              <h3 class="card-title">Sub Categories ({{ $category->name }})</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('sub_category.create', $id) }}"> <i class="fa fa-plus"></i> Create</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="table" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sr. No</th>
                  <th>Name</th>
                  <th>Image</th> 
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
        var cat_id = $(this).data('cat_id');
        var sub_cat_id = $(this).data('sub_cat_id');
        var url = '{{ route("sub_category.destroy", ["cat_id"=>":cat_id","sub_cat_id"=>":sub_cat_id"]) }}';
        url = url.replace(':cat_id', cat_id);
        url = url.replace(':sub_cat_id', sub_cat_id);
        $("#frmDel").attr('action',url);
        $("#modalDel").modal('show');
      });

		 	$("#table").DataTable({
     		processing: true,
     		serverSide: true,
     		ajax: {
     			url: "{{ route('sub_category.index', $id) }}"
       	},
       	columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex' },
          { data: 'name', name: 'name' }, 
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

		});
	</script>
@endsection

