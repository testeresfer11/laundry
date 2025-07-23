@extends('admin.layouts.app')
@section('title', 'General Information')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Config Setting</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.config-setting.smtp')}}">Settings</a></li>
        <li class="breadcrumb-item active" aria-current="page">General Setting</li>
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
                    <h4 class="card-title">General Information</h4>
                    <form class="forms-sample" id="general-information" action="{{route('admin.config-setting.general-setting')}}" method="POST">
                    @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="exampleInputFromEmail">Received Point Per Order</label>
                                    <input type="number" class="form-control  @error('received_point_per_order') is-invalid @enderror" id="receivedPointPerOrder" placeholder="Received Point Per Order" name="received_point_per_order" value="{{$generalDetail['received_point_per_order'] ?? ''}}">
                                    @error('received_point_per_order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="col-6">
                                    <label for="exampleInputFromEmail">Received Point Percentage</label>
                                    <input type="number" class="form-control  @error('received_point_percentage') is-invalid @enderror" id="exampleInputFromEmail" placeholder="Received Point Percentage" name="received_point_percentage" value="{{$generalDetail['received_point_percentage'] ?? ''}}">
                                    @error('received_point_percentage')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                                <div class="col-6">
                                    <label for="exampleInputFromEmail">Rate of Received Points (in $) </label>
                                    <input type="number" step="0.01" class="form-control  @error('received_point_rate') is-invalid @enderror" id="receivedPointRate" placeholder="Conversion Rate for Received Point" name="received_point_rate" value="{{$generalDetail['received_point_rate'] ?? ''}}" style="pointer-events: none;">
                                    @error('received_point_rate')
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
                                    <label for="exampleInputHost">Maximum Received Point Used Per Order</label>
                                    <input type="number" class="form-control @error('maximum_received_point_used_per_order') is-invalid @enderror" id="maximumPointUsed" placeholder="Maximum Received Point Used Per Order" name="maximum_received_point_used_per_order" value="{{$generalDetail['maximum_received_point_used_per_order'] ?? ''}}">
                                    @error('maximum_received_point_used_per_order')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="exampleInputHost">Expiry Points</label>
                                    <input type="number" class="form-control @error('expiry_points') is-invalid @enderror" id="expiryPoint" placeholder="Expiry Points" name="expiry_points" value="{{$generalDetail['expiry_points'] ?? ''}}">
                                    @error('expiry_points')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="col-6">
                                    <label for="exampleInputPort">Purchased Point (Per Point)</label>
                                    <input type="number" class="form-control @error('purchased_point') is-invalid @enderror" id="exampleInputPort" placeholder="Purchased Point" name="purchased_point" value="{{$generalDetail['purchased_point'] ?? ''}}"  min="0">
                                    @error('purchased_point')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="exampleInputHost">Expiry Period</label>
                                    <input type="number" class="form-control @error('expiry_period') is-invalid @enderror" id="expiryPeriod" placeholder="Expiry Period" name="expiry_period" value="{{$generalDetail['expiry_period'] ?? ''}}">
                                    @error('expiry_period')
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
    $("#general-information").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            received_point_per_order: {
                required: true,
                number: true,
                minlength:3,
            },
            received_point_rate: {
                required: true,
                minlength:1,
            },
            maximum_received_point_used_per_order: {
                required: true,
                number: true,
                minlength:3,
            },
            expiry_points: {
                required: true,
                number: true,
                minlength:3,
            },
            expiry_period: {
                required: true,
                number: true,
                minlength:1,
            },
            
        },
        messages: {
            received_point_per_order: {
                required: 'Received Point Per Order is required.',
                minlength:'Received Point Per Order must be atleast 3 digit',
            },
            received_point_rate: {
                required: 'Rate of Received Point is required.',
                minlength:'Rate of Received Point must be atleast 1 digit',
            },
            maximum_received_point_used_per_order: {
                required: 'Maximum Received Point Used Per Order is required.',
                minlength:'Maximum Received Point Used Per Order must be atleast 3 digit',
            },
            expiry_points: {
                required: 'Expiry Points is required.',
                minlength:'Expiry Points must be atleast 3 digit',
            },
            expiry_period: {
                required: 'Expiry Period is required.',
                minlength:'Expiry Period must be atleast 1 digit',
            },
        },
        submitHandler: function(form) {
            form.submit();
        }

    });
  });

    document.addEventListener("DOMContentLoaded", function () {
        const maximumPointUsedInput = document.getElementById('maximumPointUsed');
        const rateInput = document.getElementById('receivedPointRate');

        maximumPointUsedInput.addEventListener('input', function () {
            const maximumUsedPoint = parseFloat(this.value);

            if (!isNaN(maximumUsedPoint) && maximumUsedPoint > 0) {
                const calculatedRate = (maximumUsedPoint / 500).toFixed(2);
                rateInput.value = calculatedRate;
            } else {
                rateInput.value = ''; // clear the rate if input is invalid or empty
            }
        });
    });
  </script>
@stop