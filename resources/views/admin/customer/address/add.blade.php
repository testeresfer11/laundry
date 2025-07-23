@extends('admin.layouts.app')
@section('title', 'Add Customer Address')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Customer Address</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.list')}}">Customers</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.address.list',['id'=> $user->id])}}">Addresses</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add Customer Address</li>
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
                    <h4 class="card-title">Add Customer Address</h4>
                    <form class="forms-sample" id="add-customer-address" action="{{route('admin.customer.address.add',['id' => $user->id])}}" method="POST">
                    @csrf
                        <fieldset>
                            <legend>Personal Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputFirstName">First Name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="exampleInputFirstName" placeholder="First Name" name="first_name" value="{{$user->first_name}}" readonly>
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputLastName">Last Name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="exampleInputLastName" placeholder="Last Name" name="last_name" value="{{$user->last_name}}" readonly>
                                        @error('last_name')
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
                                        <label for="exampleInputEmail">Email address</label>
                                        <input type="email" class="form-control  @error('email') is-invalid @enderror" id="exampleInputEmail" placeholder="Email" name="email" value="{{$user->email}}" readonly>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Address Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="house_no">House No</label>
                                        <input type="text" class="form-control @error('house_no') is-invalid @enderror" id="house_no" placeholder="House No" name = "house_no">
                                        @error('house_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="landmark">Landmark</label>
                                        <input type="text" class="form-control @error('landmark') is-invalid @enderror" id="landmark" placeholder="Landmark" name = "landmark">
                                        @error('landmark')
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
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Address" name = "address">
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="exampleInputCity">City</label>
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="exampleInputCity" placeholder="City" name="city">
                                        @error('city')
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
                                        <label for="exampleInputState">State</label>
                                        <input type="text" class="form-control @error('state') is-invalid @enderror" id="exampleInputState" placeholder="State" name="state">
                                        @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputCountry">Country</label>
                                        <select name="country" id="exampleInputCountry" class="form-control @error('country') is-invalid @enderror" >
                                            <option value="">Select Country</option>
                                            @foreach (getCommonList('country') as $key => $value)
                                                <option value="{{$value}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                            
                                        @error('country')
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
                                        <label for="exampleInputType">Type</label>
                                        <select name="type" id="exampleInputType" class="form-control @error('type') is-invalid @enderror" >
                                            <option value="home">Home</option>
                                            <option value="work">Work</option>
                                        </select>

                                        @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <input type="hidden" id="exampleInputLatitude" name="lat">
                        <input type="hidden" id="exampleInputLongitude" name="long">
                        <div class="address-item default-address mt-2">
                            <input type="checkbox" id="setAsDefault" name="default">
                            <label for="setAsDefault">Set as default address</label>
                        </div>

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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZ09dtOd8YHF_ZCbfbaaMHJKiOr26noY8&libraries=places" ></script>
<script>
    $(document).ready(function() {
        var err = false;
        var autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address'), {
                types: ['geocode']
            }
        );

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            var postalCode = '';
            var city = '';
            var state = '';
            var country = '';
            var latitude = '';
            var longitude = '';

            if (place.address_components) {
                for (var i = 0; i < place.address_components.length; i++) {
                    var component = place.address_components[i];
                    
                    if (component.types.includes('postal_code')) {
                        postalCode = component.long_name;
                    }
                    if (component.types.includes('locality')) {
                        city = component.long_name;
                    }
                    if (component.types.includes('administrative_area_level_1')) {
                        state = component.long_name;
                    }
                    if (component.types.includes('country')) {
                        country = component.long_name;
                    }
                }
                
                // Get latitude and longitude from place.geometry
                if (place.geometry && place.geometry.location) {
                    latitude = place.geometry.location.lat();
                    longitude = place.geometry.location.lng();
                }
                
                // Populate the fields
                $('#exampleInputPinCode').val(postalCode);
                $('#exampleInputCity').val(city);
                $('#exampleInputState').val(state);
                $('#exampleInputCountry').val(country);
                $('#exampleInputLatitude').val(latitude);
                $('#exampleInputLongitude').val(longitude);
            }
        });



        $("#add-customer-address").submit(function(e){
            e.preventDefault();
        }).validate({
            rules: {
                address: {
                    required: true,
                    noSpace: true,
                    minlength: 3,
                },
                type: {
                    required: true,
                },
            },
            messages: {
                address: {
                    required: "Address is required",
                    minlength: "Address must consist of at least 3 characters"
                },
                type: {
                    required: 'Please select type.',
                },
            },
            submitHandler: function(form) {
                form.submit();
            }

        });
    });
  </script>
@stop