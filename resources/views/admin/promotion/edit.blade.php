@extends('admin.layouts.app')
@section('title', 'Edit Promotion')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Promotion</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.promotion.list')}}">Promotion Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Promotion</li>
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
                    <h4 class="card-title">Edit Promotion</h4>
                
                    <form class="forms-sample" id="edit-promotion" action="{{route('admin.promotion.edit',['id'=>$promotion->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <fieldset>
                            <legend>Promotion Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputTitle">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="exampleInputTitle" placeholder="Title" name="title" value="{{$promotion->title}}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputDiscount">Discount (in %) </label>
                                        <input type="number" class="form-control @error('discount') is-invalid @enderror" id="exampleInputDiscount" placeholder="Discount" name="discount" min="1" max="100" value="{{$promotion->discount}}">
                                        @error('discount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputExpDate">Expiry Date </label>
                                        <input type="date" class="form-control @error('exp_date') is-invalid @enderror" id="exampleInputExpDate"  name="exp_date" min="{{date('Y-m-d')}}" value="{{$promotion->exp_date}}">
                                        @error('exp_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputMinOrder">Min Order</label>
                                        <input type="number" class="form-control @error('min_order') is-invalid @enderror" id="exampleInputMinOrder" placeholder="Min Order" name="min_order" min="1" value="{{$promotion->min_order}}" max="1000">
                                        @error('min_order')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputMaxDiscount">Max Discount (in $)</label>
                                        <input type="number" class="form-control @error('max_discount') is-invalid @enderror" id="exampleInputMaxDiscount" placeholder="Max Discount" name="max_discount" min="1" value="{{$promotion->max_discount}}" max="10000">
                                        @error('max_discount')
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
                                        <textarea name="description" id="description" class="form-control @error('first_name') is-invalid @enderror" cols="30" rows="5">{{$promotion->description ?? ''}}</textarea>
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
    $("#edit-promotion").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            title: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength: 100,
            },
            exp_date: {
                required: true,
            },
            discount: {
                required: true,
                number: true,
            },
            min_order: {
                required: true,
                number: true,
            },
            max_discount: {
                required: true,
                number: true,
            },

        },
        messages: {
            title: {
                required: "Title is required",
                minlength: "Title must consist of at least 3 characters",
                maxlength: "Title must not be greater than 100 characters"
            },
            exp_date: {
                required: 'Expiry date is required',
            },
            discount: {
                required: "Discount is required",
                minlength: "Discount only contain numeric value"
            },
            min_order: {
                required: "Min order is required",
                minlength: "Min order only contain numeric value"
            },
            max_discount: {
                required: "Max discount is required",
                minlength: "Max discount only contain numeric value"
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