@extends('admin.layouts.app')
@section('title', 'Edit Tax')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Tax</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.tax.list')}}">Tax Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Tax</li>
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
            <h4 class="card-title">Edit Tax</h4>
              
            <form class="forms-sample" id="edit-tax" action="{{route('admin.tax.edit',['id'=>$tax->id])}}" method="POST" enctype="multipart/form-data">
              @csrf
              <fieldset>
                <legend>Tax Information</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputName">Label</label>
                            <input type="text" class="form-control @error('label') is-invalid @enderror" id="exampleInputName" placeholder="Label" name="label" value="{{$tax->label}}">
                            @error('label')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label>Percentage % </label>
                            <input type="number" class="form-control @error('percentage') is-invalid @enderror" id="exampleInputpercentage" placeholder="Percentage" name="percentage" min="0" max="100" value="{{$tax->percentage}}">
                            @error('label')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
              </fieldset>
               
                <button type="submit" class="btn btn-primary mr-2 mt-4">Update</button>
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
    $("#edit-tax").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            label: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength: 25
            },
            percentage: {
                required: true,
                number: true,
                maxlength: 3,
            },
           
        },
        messages: {
            label: {
                required: "Label is required",
                minlength: "Label must consist of at least 3 characters",
                maxlength: "Label must not be greater than 25 characters"
            },
            percentage: {
                required: "Percentage is required",
                number: "only numeric value is acceptable",
                maxlength: "Percentage must not be greater than 3 digits"
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