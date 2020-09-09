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
            <h1>Product Edit</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Products</a></li>
              <li class="breadcrumb-item active">Product Edit</li>
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
              <h3 class="card-title">Product Edit</h3>
              <div class="text-right">
                <a class="btn btn-primary" href="{{ route('product.index') }}"><i class="fa fa-backward"></i> Back</a>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              {!! Form::model($product, ['method' => 'PATCH','enctype' => 'multipart/form-data','route' => ['product.update', $product->product_id]]) !!}
              <div class="row">
                <div class="col-6">
                  <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                      <strong>Category:</strong>
                      <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">Select Category</option>
                        @foreach($category as $value)
                        <option {{ ($product->category_id == $value->category_id) ? 'selected' : '' }} value="{{ $value->category_id }}">{{ $value->name }}</option>
                        @endforeach
                      </select> 
                      @error('category_id')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Sub Category:</strong>
                      <select name="sub_category_id" id="sub_category_id" class="form-control @error('sub_category_id') is-invalid @enderror">
                        <option value="">Select Sub Category</option>
                      </select> 
                      @error('sub_category_id')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Product Code:</strong>
                      <input id="product_code" type="text" class="form-control @error('product_code') is-invalid @enderror" name="product_code" autocomplete="product_code" placeholder="Product Code" value="{{ $product->product_code }}">
                      @error('product_code')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Weight:</strong>
                      <input id="weight" type="text" class="form-control @error('weight') is-invalid @enderror" name="weight" autocomplete="weight" placeholder="Weight" value="{{ $product->weight }}">
                      @error('weight')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <strong>Stone/Diamond:</strong>
                      <input id="stone" type="text" class="form-control @error('stone') is-invalid @enderror" name="stone" autocomplete="stone" placeholder="Stone/Diamond" value="{{ $product->stone }}">
                      @error('stone')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="form-group" id="KT">
                      <strong>KT:</strong><br>
                      <label><input @if( in_array( "18", explode(",", $product->kt)) ) checked @endif type="checkbox" name="kt[]"  class="@error('kt') is-invalid @enderror" value="18"></label> 18 
                      <br>
                      <label><input @if( in_array( "22", explode(",", $product->kt)) ) checked @endif type="checkbox" name="kt[]" class="@error('kt') is-invalid @enderror" value="22"></label> 22
                      <br>
                      @if ($errors->has('kt'))
                        <span class="text-danger"> <strong>{{ $errors->first('kt') }}</strong></span>
                      @endif 
                    </div>
                    <div class="form-group">
                      <a data-magnify="gallery" href="{{ Storage::disk('s3')->url($product->image) }}"> <img class="img-responsive" height="100" src="{{ Storage::disk('s3')->url($product->image) }}"></a>
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
  <script type="text/javascript">
    $(document).ready(function() {
      setTimeout(function(){
        $("#category_id").trigger('change');
      },500);

      $('#category_id').change(function(){ 
        var category_id=$(this).val(); 
        if(category_id == 2){
          $("#KT").hide();
        }else{
          $("#KT").show();
        }
        var url = '{{ route("sub.category", ":id") }}';
        url = url.replace(':id', category_id);
        $.ajax({
          url: url,
          success: function(result){
            if(result){
              $('#sub_category_id').html(result); 
              $('#sub_category_id option[value="{{ $product->sub_category_id }}"]').attr("selected","selected");
            }else{

            }         
          } 
        });
      });

    });
  </script>
@endsection