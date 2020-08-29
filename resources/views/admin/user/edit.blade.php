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
            <h1>User Edit</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('user.index') }}">users</a></li>
              <li class="breadcrumb-item active">User Edit</li>
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
              <h3 class="card-title">user Edit</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}"><i class="fa fa-backward"></i> Back</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              {!! Form::model($user, ['method' => 'PATCH','enctype' => 'multipart/form-data','route' => ['user.update', $user->id]]) !!}
              <div class="row">
                <div class="col-6">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                      <strong>First Name:</strong>
                      <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" autocomplete="first_name" placeholder="First Name" value="{{ $user->first_name }}"> 
                      @error('first_name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Last Name:</strong>
                      <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" autocomplete="last_name" placeholder="Last Name" value="{{ $user->last_name }}"> 
                      @error('last_name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Email:</strong>
                      <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" autocomplete="email" placeholder="Email" value="{{ $user->email }}"> 
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Mobile:</strong>
                      <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" autocomplete="mobile" placeholder="Mobile" value="{{ $user->mobile }}"> 
                      @error('mobile')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <a data-magnify="gallery" href="{{ Storage::disk('s3')->url($user->image) }}"> <img class="img-responsive" height="100" src="{{ Storage::disk('s3')->url($user->image) }}"></a>
                    </div>
                    <div class="form-group">
                      <strong>Image:</strong>
                      <input id="image" type="file" class="form-control @error('image') is-invalid @enderror" name="image" >
                      @error('image')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
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