@extends('admin.layouts.app')
@section('title', 'View Points')
@section('breadcrum')
    <div class="page-header">
        <h3 class="page-title">Points</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.points.list') }}">Points Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Offer</li>
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
            <h4 class="card-title">View Offer</h4>
            <form class="forms-sample">
              <fieldset>
                <legend>Offer Information</legend>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputOfferName">Offer Name</label>
                            <input type="text" class="form-control" id="exampleInputOfferName" placeholder="Offer Name" name="offer_name" value="{{$point->offer_name}}" readonly>
                        </div>
                        <div class="col-6">
                            <label for="examplePoints">Points</label>
                            <input type="number" class="form-control" id="examplePoints" placeholder="points" name="points" min="1" max="9999"value="{{$point->points}}" readonly >
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputoffer_type">Offer Type</label>
                            <select name="offer_type" id="exampleInputoffer_type" class="form-control">
                                <option value="">Select Offer Type</option>
                                <option value="discount" {{($point->offer_type == 'discount') ? 'selected' : ''}}>Discount</option>
                                <option value="free_service" {{($point->offer_type == 'free_service') ? 'selected' : ''}}>Free Service</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="exampleInputMaxOrder">Maximum Order</label>
                            <input type="number" class="form-control" id="exampleInputMaxOrder" placeholder="Max Order" name="max_order_amount" min="1" max="1000" value="{{$point->max_order_amount}}" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <label for="exampleInputStartDate">Start Date </label>
                            <input type="date" class="form-control" id="exampleInputStartDate"  name="start_date" min="{{date('Y-m-d')}}" value="{{$point->start_date}}" readonly>
                        </div>

                        <div class="col-6">
                            <label for="exampleInputEndDate">End Date </label>
                            <input type="date" class="form-control" id="exampleInputEndDate"  name="end_date" min="{{date('Y-m-d')}}" value="{{$point->end_date}}" readonly>
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-12">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" cols="30" rows="5" readonly>{{$point->description}}</textarea>
                        </div>
                    </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
</div> 
@endsection
