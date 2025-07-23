@extends('admin.layouts.app')
@section('title', 'View Promotion')
@section('breadcrum')
    <div class="page-header">
        <h3 class="page-title">Promotion</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.promotion.list') }}">Promotion Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Promotion</li>
            </ol>
        </nav>
    </div>
@endsection
@section('content')
<div>
    <h4 class="user-title">View Promotion</h4>
    <div class="card">
        <div class="card-body">
            <form class="forms-sample">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3">
                            <div class="view-user-details">
                                <div class="text-center">
                                    <img class="promotion-image"
                                        @if ( !is_null($promotion->image)) 
                                            src="{{ asset('storage/images/' . $promotion->image) }}"
                                        @else
                                            src="{{ asset('admin/images/faces/face15.jpg') }}" 
                                        @endif
                                        onerror="this.src = '{{ asset('admin/images/faces/face15.jpg') }}'"
                                        alt="Promotion profile picture">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="response-data ml-4">
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Title :</span> 
                                    <span class="text-muted">{{ $promotion->title }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Discount (in %) :</span> 
                                    <span class="text-muted">{{ $promotion->discount ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Expiry Date :</span> 
                                    <span class="text-muted">{{ $promotion->exp_date ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Min Order (in $) :</span> 
                                    <span class="text-muted">{{ $promotion->min_order ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Max Discount (in $) :</span> 
                                    <span class="text-muted">{{ $promotion->max_discount ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Date &amp; time :</span> 
                                    <span class="text-muted" >{{ convertDate($promotion->created_at) }}</span>
                                </h6>
                            </div>
                            <h6 class="mt-3 ml-4"><span class="semi-bold qury">Description :</span> <br>
                                    <span class="text-muted">{{ $promotion->description ?? '' }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
