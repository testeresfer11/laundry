@extends('admin.layouts.app')
@section('title', 'Wallet')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Wallet</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
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
            <h4 class="card-title">Wallet</h4>
            <div class="admin-filters">
              <form id="filter">
                <div class="row align-items-center justify-content-end mb-3">
                    <div class="col-6 d-flex gap-2">
                        <input type="text" class="form-control"  placeholder="Search keyword" name="search_keyword" value="{{request()->filled('search_keyword') ? request()->search_keyword : ''}}">            
                    </div>
                
                    <div class="col-6 text-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request()->filled('search_keyword') || request()->filled('status') || request()->filled('category_id'))
                            <button class="btn btn-danger" id="clear_filter">Clear Filter</button>
                        @endif
                    </div>
                </div>
              </form>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Wallet Id</th>
                  <th> User Name</th>
                  <th> Balance</th>
                  <th> Updated Date</th>
                  <th> Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($wallets as $key => $data)
                  <tr>
                    <td> {{ $data->wallet_id ?? 'N/A' }} </td>
                    <td> <a href = {{route('admin.customer.view',['id' => $data->user_id])}}>{{UserNameById($data->user_id)}} </a></td>
                    <td> ${{$data->amount}} </td>
                    <td> {{convertDate($data->updated_at,'d M Y')}} </td>
                    <td> 
                      <span class="menu-icon">
                        <a href="{{route('admin.wallet.view',['id' => $data->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                      </span>&nbsp;&nbsp;&nbsp;
                    </td>
                  </tr>
                @empty
                    <tr>
                      <td colspan="5" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="custom_pagination">
            {{ $wallets->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

