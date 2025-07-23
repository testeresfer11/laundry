@extends('admin.layouts.app')
@section('title', 'Profile Detail')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Settings</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
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
            <h4 class="card-title">Personal Information</h4>
            <form class="forms-sample" id="profile-setting" action="{{ route('admin.profile') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <div class="row">
                    <div class="col-6">
                        <label for="exampleInputFirstName">First Name</label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="exampleInputFirstName" placeholder="First Name" name="first_name" value="{{ $user->first_name ?? '' }}">
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="exampleInputLastName">Last Name</label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="exampleInputLastName" placeholder="Last Name" name="last_name" value="{{ $user->last_name ?? '' }}">
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
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail" placeholder="Email" name="email" value="{{ $user->email ?? '' }}" readonly>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="exampleInputPhoneNumber">Phone Number</label>
                        <input type="number" class="form-control @error('phone_number') is-invalid @enderror" id="exampleInputPhoneNumber" placeholder="Phone Number" name="phone_number" value="{{ $user->userDetail ? ($user->userDetail->phone_number ?? '') : '' }}" min="0">
                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
              </div>

              <div class="form-group">
                <label>Profile upload</label>
                <div class="input-group col-xs-12">
                  <input type="file" name="profile" class="form-control file-upload-info" placeholder="Upload Image" accept="image/*">
                </div>
              </div>

              <fieldset>
                <legend>Address Information</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                        
                            <label for="address">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" placeholder="Address" name="address" value="{{ $user->userAddress->address ?? '' }}">
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="exampleInputCity">City</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="exampleInputCity" placeholder="City" name="city" value="{{ $user->userAddress->city ?? '' }}">
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
                            <input type="text" class="form-control @error('state') is-invalid @enderror" id="exampleInputState" placeholder="State" name="state" value="{{ $user->userAddress->state ?? '' }}">
                            @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="exampleInputCountry">Country</label>
                            <select name="country" id="exampleInputCountry" class="form-control @error('country') is-invalid @enderror">
                                <option value="">Select Country</option>
                                @foreach (getCommonList('country') as $key => $value)
                                    <option value="{{ $value }}" {{ (isset($user->userAddress) && $user->userAddress->country == $value) ? 'selected' : '' }}>{{ $value }}</option>
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
                            <select name="type" id="exampleInputType" class="form-control @error('type') is-invalid @enderror">
                                <option value="home" {{ (isset($user->userAddress) && $user->userAddress->type == 'home') ? 'selected' : '' }}>Home</option>
                                <option value="work" {{ (isset($user->userAddress) && $user->userAddress->type == 'work') ? 'selected' : '' }}>Work</option>
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
            <!-- Hidden fields for latitude and longitude -->
            <input type="hidden" name="latitude" id="latitude" value="{{ $user->addressDetail->lat ?? '' }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ $user->addressDetail->long ?? '' }}">

            <button type="submit" class="btn btn-primary mr-2">Update</button>
          </form>
          </div>
        </div>
      </div>
    </div>
</div>    

@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZ09dtOd8YHF_ZCbfbaaMHJKiOr26noY8&libraries=places"></script>
<script>
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
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
        }
    });

    $(document).ready(function() {
        $("#profile-setting").submit(function(e){
            e.preventDefault();
        }).validate({
            rules: {
                first_name: {
                    required: true,
                    noSpace: true,
                    minlength: 3,
                    maxlength: 25,
                },
                last_name: {
                    required: true,
                    noSpace: true,
                    minlength: 3,
                    maxlength: 25,
                },
                email: {
                    required: true,
                    email: true
                },
                phone_number: {
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                    noSpace: true,
                },
            },
            messages: {
                first_name: {
                    required: "First name is required",
                    minlength: "First name must consist of at least 3 characters",
                    maxlength: "First name must not be greater than 25 characters"
                },
                last_name: {
                    required: "Last name is required",
                    minlength: "Last name must consist of at least 3 characters",
                    maxlength: "Last name must not be greater than 25 characters"
                },
                email: {
                    email: "Please enter a valid email address"
                },
                phone_number: {
                    number: 'Only numeric value is acceptable',
                    minlength:  'Phone number must be 10 digits',
                    maxlength:  'Phone number must be 10 digits'
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                var action = $(form).attr('action'); // Use the form's action attribute
                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        // Handle success
                        if (response.status == "success") {
                            toastr.success(response.message);
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        // Handle error 
                        let response = xhr.responseJSON;
                        toastr.error(response.message);
                    }
                });
            }
        });
    });
</script>
@stop
