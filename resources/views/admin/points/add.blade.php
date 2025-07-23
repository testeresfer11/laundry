@extends('admin.layouts.app')
@section('title', 'Add Point Offer')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Points</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.points.list')}}">Points Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Offer</li>
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
                    <h4 class="card-title">Add Offer</h4>
                    
                    <form class="forms-sample" id="add-point" action="{{route('admin.points.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <fieldset>
                            <legend>Offer Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputOfferName">Offer Name</label>
                                        <input type="text" class="form-control @error('offer_name') is-invalid @enderror" id="exampleInputOfferName" placeholder="Offer Name" name="offer_name">
                                        @error('offer_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="examplePoints">Points</label>
                                        <input type="number" class="form-control @error('points') is-invalid @enderror" id="examplePoints" placeholder="points" name="points" min="1" max="9999" >
                                        @error('points')
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
                                        <label for="exampleInputoffer_type">Offer Type</label>
                                        <select name="offer_type" id="exampleInputoffer_type" class="form-control  @error('offer_type') is-invalid @enderror" >
                                            <option value="">Select Offer Type</option>
                                            <option value="discount">Discount</option>
                                            <option value="free_service">Free Service</option>
                                        </select>
                                        @error('offer_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputMaxOrder">Maximum Order</label>
                                        <input type="number" class="form-control @error('max_order_amount') is-invalid @enderror" id="exampleInputMaxOrder" placeholder="Max Order" name="max_order_amount" min="1" max="1000">
                                        @error('max_order_amount')
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
                                        <label for="exampleInputStartDate">Start Date </label>
                                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="exampleInputStartDate"  name="start_date" min="{{date('Y-m-d')}}">
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="exampleInputEndDate">End Date </label>
                                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="exampleInputEndDate"  name="end_date" min="{{date('Y-m-d')}}">
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" cols="30" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    
                        <button type="submit" class="btn btn-primary mr-2 mt-4">Add</button>
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
    $("#add-point").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            offer_name: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength:100
            },
            offer_type: {
                required: true,
            },
            points: {
                required: true,
                number: true,
            },
        },
        messages: {
            offer_name: {
                required: "Offer name is required",
                minlength: "Offer name must consist of at least 3 characters",
                maxlength: "Offer name must not be greater than 100 characters",
            },
            offer_type: {
                required: 'Offer type is required',
            },
            points: {
                required: "Points is required",
                minlength: "Points only contain numeric value"
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