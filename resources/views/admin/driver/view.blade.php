@extends('admin.layouts.app')
@section('title', 'View Driver')
@section('breadcrum')
    <div class="page-header">
       <h3 class="page-title">Drivers</h3>
       <nav aria-label="breadcrumb">
           <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="#">User Management</a></li>
               <li class="breadcrumb-item"><a href="{{ route('admin.driver.list') }}">Drivers</a></li>
               <li class="breadcrumb-item active" aria-current="page">View Driver</li>
           </ol>
       </nav>
   </div>
@endsection
@section('content')
<div>
    <h4 class="user-title">View Driver</h4>
    <div class="card">
        <div class="card-body">
            <form class="forms-sample">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3">
                            <div class="view-user-details">
                                <div class="text-center">
                                    <img class="driver-image"
                                        @if (isset($user->driverDetail) && !is_null($user->driverDetail->profile))
                                            src="{{ asset('storage/images/' . $user->driverDetail->profile) }}"
                                        @else
                                            src="{{ asset('admin/images/faces/user_dummy.png') }}"
                                        @endif
                                        oner ror="this.src = '{{ asset('admin/images/faces/user_dummy.png') }}'"
                                        alt="User profile picture">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="response-data ml-4">
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Name :</span>
                                    <span class="text-muted">{{ $user->full_name }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Email :</span>
                                    <span class="text-muted">{{ $user->email ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Gender :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->gender ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Phone Number :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->phone_number ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Date Of Birth :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->dob ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Country Short Code :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? ($user->driverDetail->country_short_code ? strtoupper($user->driverDetail->country_short_code) : 'N/A') : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Address :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->address ?? 'N/A' : 'N/A' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Vehicle Type :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->vehicle->name ?? 'N/A' : 'N/A  ' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">License Number :</span>
                                    <span class="text-muted">{{ $user->driverDetail ? $user->driverDetail->license_number ?? 'N/A' : 'N/A  ' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Date &amp; time :</span>
                                    <span class="text-muted " >{{ convertDate($user->created_at) }}</span>
                                </h6>
                            </div>
                            <h6 class="f-14  mt-3 ml-4"><span class="semi-bold qury">Description :</span><br>
                                <span class="text-muted text-area">{{ $user->driverDetail ? $user->driverDetail->description ?? 'N/A' : 'N/A  ' }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection