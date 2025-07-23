@extends('admin.layouts.app')
@section('title', 'Delivery Cost Price')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Config Setting</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.config-setting.stripe')}}">Settings</a></li>
        <li class="breadcrumb-item active" aria-current="page">Delivery Cost</li>
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
                    <h4 class="card-title">Delivery Cost Price</h4>
                    
                    <form class="forms-sample" id="delivery-cost-price-information" action="{{route('admin.config-setting.delivery-cost')}}" method="POST">
                    @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label >Price Per Kilometer</label>
                                    <input type="number" class="form-control @error('DELIVERY_CHARGE') is-invalid @enderror" placeholder="Price Per Kilometer" name = "DELIVERY_CHARGE" value="{{$detail['DELIVERY_CHARGE'] ?? ''}}" min="0">
                                    @error('DELIVERY_CHARGE')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label>Minimum order amount</label>
                                    <input type="number" class="form-control @error('MINIMUM_ORDER_AMOUNT') is-invalid @enderror" placeholder="Minimum order amount" name="MINIMUM_ORDER_AMOUNT" value="{{$detail['MINIMUM_ORDER_AMOUNT'] ?? ''}}"  min="0">
                                    @error('MINIMUM_ORDER_AMOUNT')
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
                                    <label>Free Delivery Threshold</label>
                                    <input type="number" class="form-control @error('FREE_DELIVERY') is-invalid @enderror" placeholder="Free Delivery Threshold" name="FREE_DELIVERY" value="{{$detail['FREE_DELIVERY'] ?? ''}}"  min="0">
                                    @error('FREE_DELIVERY')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    <button type="submit" class="btn btn-primary mr-2">Update</button>
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
    $("#delivery-cost-price-information").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            DELIVERY_CHARGE: {
                required: true,
                number: true,
            },
            FREE_DELIVERY: {
                required: true,
                number: true,
            },
            MINIMUM_ORDER_AMOUNT: {
                required: true,
                number: true,
            },
        },
        messages: {
            DELIVERY_CHARGE: {
                required: "Delivery charge is required",
                number: "Delivery charge must only accept numeric value"
            },
            FREE_DELIVERY: {
                required: "Free delivery is required",
                number: "Free delivery must only accept numeric value"
            },
            MINIMUM_ORDER_AMOUNT: {
                required: "Minimum order amount is required",
                number: "Minimum order amount must only accept numeric value"
            },
        },
        submitHandler: function(form) {
            form.submit();
        }

    });
  });
  </script>
@stop