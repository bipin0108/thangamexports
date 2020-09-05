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
            <h1>Sub Category Edit</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('sub_category.index', $cat_id) }}">Category</a></li>
              <li class="breadcrumb-item active">Sub Category Edit</li>
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
              <h3 class="card-title">Category Edit</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('sub_category.index', $cat_id) }}"><i class="fa fa-backward"></i> Back</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              {!! Form::model($sub_category, ['method' => 'PATCH','enctype' => 'multipart/form-data','route' => ['sub_category.update', ['cat_id'=>$cat_id,'sub_cat_id'=>$sub_category->sub_category_id] ]]) !!}
              <div class="row">
                <div class="col-6">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                      <strong>Name:</strong>
                      <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" autocomplete="name" placeholder="Name" value="{{ $sub_category->name }}">
                      @error('name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <a data-magnify="gallery" href="{{ Storage::disk('s3')->url($sub_category->image) }}"> <img class="img-responsive" height="100" src="{{ Storage::disk('s3')->url($sub_category->image) }}"></a>
                    </div>
                    <div class="form-group">
                      <strong>Image:</strong>
                      <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" >
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
              {!! Form::close() !!}
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
 
@endsection