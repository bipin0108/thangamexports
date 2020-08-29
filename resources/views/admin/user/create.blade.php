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
            <h1>User Create</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('user.index') }}">Users</a></li>
              <li class="breadcrumb-item active">User Create</li>
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
              <h3 class="card-title">User Create</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('user.index') }}"><i class="fa fa-backward"></i> Back</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               {!! Form::open(array('route' => 'user.store','method'=>'POST','enctype' => 'multipart/form-data')) !!}
                <div class="row">
                  <div class="col-6">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                      <div class="form-group">
                        <strong>First Name:</strong>
                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" autocomplete="first_name" placeholder="First Name"> 
                        @error('first_name')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <strong>Last Name:</strong>
                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" autocomplete="last_name" placeholder="Last Name"> 
                        @error('last_name')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <strong>Email:</strong>
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" autocomplete="email" placeholder="Email"> 
                        @error('email')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <strong>Password:</strong>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="password" placeholder="Password"> 
                        @error('password')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <strong>Mobile:</strong>
                        <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" autocomplete="mobile" placeholder="Mobile"> 
                        @error('mobile')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
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
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
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

