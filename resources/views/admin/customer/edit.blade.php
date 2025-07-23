@extends('admin.layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/css/intlTelInput.css">
@endsection
@section('title', 'Edit Customer')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Customers</h3>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.customer.list')}}">Customers</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Customer</li>
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
                    <h4 class="card-title">Edit Customer</h4>
                    <form class="forms-sample" id="Edit-Customer" action="{{route('admin.customer.edit',['id' => $user->id])}}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <fieldset>
                            <legend>Personal Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputFirstName">Profile</label>
                                        <img 
                                            class=" img-lg  rounded-circle"
                                            @if(isset($user->userDetail) && !is_null($user->userDetail->profile))
                                                src="{{ asset('storage/images/' . $user->userDetail->profile) }}"
                                            @else
                                                src="{{ asset('admin/images/faces/face15.jpg') }}"
                                            @endif
                                            onerror="this.src = '{{ asset('admin/images/faces/face15.jpg') }}'"
                                            alt="Customer profile picture">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputFirstName">First Name</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="exampleInputFirstName" placeholder="First Name" name="first_name" value="{{$user->first_name ?? ''}}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputLastName">Last Name</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="exampleInputLastName" placeholder="Last Name" name="last_name" value="{{$user->last_name ?? ''}}">
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
                                        <input type="email" class="form-control  @error('email') is-invalid @enderror" id="exampleInputEmail" placeholder="Email" name="email" value="{{$user->email ?? ''}}" readonly>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label for="exampleInputGender">Gender</label>
                                        <select name="gender" id="exampleInputGender" class="form-control  @error('gender') is-invalid @enderror" >
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{$user->userDetail ? (($user->userDetail->gender == 'Male' ) ? 'selected': '') : ''}}>Male</option>
                                            <option value="Female" {{$user->userDetail ? (($user->userDetail->gender == 'Female' ) ? 'selected': '') : ''}}>Female</option>
                                            <option value="Other" {{$user->userDetail ? (($user->userDetail->gender == 'Female' ) ? 'selected': '') : ''}}>Other</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6 select_country_code">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone" placeholder="Phone Number" name="phone_number" value="{{$user->userDetail ? ($user->userDetail->phone_number ?? '') : ''}}" pattern="\d*" inputmode="numeric" 
                                        oninput="this.value = this.value.replace(/\D/g, '')">
                                        <input type="hidden" name="country_code" value="">
                                        <input type="hidden" name="country_short_code" value="{{$user->userDetail ? ($user->userDetail->country_short_code ?? 'us') : 'us'}}">
                                        @error('phone_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                
                                    <div class="col-6">
                                        <label for="dob">Date Of Birth</label>
                                        <input type="date" class="form-control  @error('dob') is-invalid @enderror" id="dob" placeholder="dob" name="dob"  value="{{$user->userDetail ? ($user->userDetail->dob ?? '') : ''}}">
                                        @error('dob')
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
                                        <label for="exampleInputPassword">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword" placeholder="Password" name="password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Account Information</legend>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="exampleInputStatus">Status</label>
                                        <select name="status" id="exampleInputStatus" class="form-control  @error('status') is-invalid @enderror" >
                                            <option value="1" {{($user->status == '1' ) ? 'selected': ''}}>Active</option>
                                            <option value="0" {{($user->status == '0' ) ? 'selected': ''}}>In Active</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label>Profile upload</label>
                                        <input type="file" name="profile" class="form-control file-upload-info" placeholder="Upload Image" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary mr-2 mt-4" >Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    
@endsection
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZ09dtOd8YHF_ZCbfbaaMHJKiOr26noY8&libraries=places" ></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/js/intlTelInput.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input            = document.querySelector("#phone");
        const countryShortCode = document.querySelector("input[name='country_short_code']").value;
        
        const iti = window.intlTelInput(input, {
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.6.1/build/js/utils.js",
            initialCountry: countryShortCode,
            formatOnDisplay: false, 
            nationalMode: false,
        });

        
        document.querySelector("#phone").addEventListener("input", function(event) {
            const countryData = iti.getSelectedCountryData();
            let phoneNumber = iti.getNumber("e164");
            phoneNumber = phoneNumber.replace(/\D/g, '');
            
            document.querySelector("input[name='country_code']").value = countryData.dialCode;
            document.querySelector("input[name='country_short_code']").value = countryData.iso2;
            input.value = phoneNumber;
        });
    });
</script>

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
        if (place.address_components) {
            for (var i = 0; i < place.address_components.length; i++) {
                var component = place.address_components[i];
                if (component.types.includes('postal_code')) {
                    postalCode = component.long_name;
                    break;
                }
            }
            $('#exampleInputPinCode').val(postalCode);
        }
    });


    $("#Edit-Customer").submit(function(e){
        e.preventDefault();
    }).validate({
        rules: {
            first_name: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength:25
            },
            last_name: {
                required: true,
                noSpace: true,
                minlength: 3,
                maxlength:25
            },
            email: {
                required: true,
                email: true
            },
            phone_number: {
                number: true,
                // minlength:10,
                maxlength: 10,
            },
            password:{
                minlength:6,
            },
            dob: {
                required: true,
                date: true,
                dobValidation: true // Custom validation rule for DOB
            }
        },
        messages: {
            first_name: {
                required: "First name is required",
                minlength: "First name must consist of at least 3 characters"
            },
            last_name: {
                required: "Last name is required",
                minlength: "Last name must consist of at least 3 characters"
            },
            email: {
                email: "Please enter a valid email address"
            },
            phone_number: {
                number: 'Only numeric value is acceptable',
                minlength:  'Phone number must be 10 digits',
                maxlength:  'Phone number must be 10 digits'
            },
            password: {
                minlength: "Password must consist of at least 6 characters"
            },
            dob: {
                required: "Date of birth is required.",
                dobValidation: "You must be at least 13 years old." // Custom error message for DOB validation
            }
        },
        submitHandler: function(form) {
          form.submit();
        },
        onkeyup: function(element) {
            this.element(element);
        }

    });

        // Define custom rule for DOB
        $.validator.addMethod("dobValidation", function(value, element) {
            const dob = new Date(value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age >= 13;
        }, "You must be at least 13 years old.");

  });
  </script>
@stop