@extends('admin.layouts.app')
@section('title', 'Add Service')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Service</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Product Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.service.list')}}">Services</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Service</li>
      </ol>
    </nav>
</div>
@endsection
@section('content')
<div>
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Add Service</h4>
              
            <form class="forms-sample" id="add-service" action="{{route('admin.service.add')}}" method="POST" enctype="multipart/form-data">
              @csrf
              <fieldset>
                <legend>Service Information</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputName">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="exampleInputName" placeholder="Name" name="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label>Image</label>
                            <div class="input-group col-xs-12">
                            <input type="file" name="image" class="form-control file-upload-info @error('image') is-invalid @enderror" placeholder="Upload Image" accept="image/*">
                            @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('first_name') is-invalid @enderror" cols="30" rows="5"></textarea>
                        </div>
                    </div>
                </div>
              </fieldset>
               
                <button type="submit" class="btn btn-primary mr-2 mt-4">Add</button>
              {{-- <button class="btn btn-dark">Cancel</button> --}}
            </form>
          </div>
        </div>
      </div>
    </div>
</div>    
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $("#add-service").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            name: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength: 25,
            },
            image: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Service name is required",
                minlength: "Service name must consist of at least 3 characters",
                maxlength: "Service name must not be greater than 25 characters"
            },
            image: {
                required: "Image is required",
            },
        },
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            if (element.prop('type') === 'file') {
                error.insertAfter(element.closest('.form-control'));
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
  </script>
@stop