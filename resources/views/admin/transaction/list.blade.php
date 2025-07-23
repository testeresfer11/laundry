@extends('admin.layouts.app')
@section('title', 'Transaction')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Transactions</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#">Order Management</a></li>
        <li class="breadcrumb-item active" aria-current="page">Transactions</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
         
          
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h4 class="card-title">Transactions</h4>
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Sr. No. </th>
                  <th> Transaction Id</th>
                  <th> User Name</th>
                  <th> Amount</th>
                  <th> Order Id</th>
                  <th> Transaction Date</th>
                  <th> Type</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($transactions as $key => $data)
                  <tr>
                    <td> {{ ++$key }} </td>
                    <td> {{$data->payment_id ?? 'N/A'}} </td>
                    <td> {{UserNameById($data->user_id)}} </td>
                    <td> ${{$data->amount}} </td>
                    <td> {{$data->order ? $data->order->order_id : 'N/A'}} </td>
                    <td> {{convertDate($data->created_at,'d M Y')}} </td>
                    <td> {{$data->payment_type ?? 'N/A'}} </td>
                  </tr>
                @empty
                    <tr>
                      <td colspan="7" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="custom_pagination">
            {{ $transactions->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

