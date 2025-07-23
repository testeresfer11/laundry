@extends('admin.layouts.app')
@section('title', 'Wallet')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title">Wallet</h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{route('admin.wallet.list')}}">Wallet</a></li>
        <li class="breadcrumb-item active" aria-current="page">View</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')

<div>
    <h3 class="mt-2"> Detail </h3>
    <div class="card">
        <div class="card-body">
            <form class="forms-sample">
                <div class="form-group">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-12">
                            <div class="response-data ml-4">
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Wallet Id :</span> 
                                    <span class="text-muted" >{{ $wallet->wallet_id }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">User Name :</span> 
                                    <span class="text-muted" >{{ UserNameById($wallet->user_id) ?? '' }}</span>
                                </h6>
                                <h6 class="f-14 mb-1"><span class="semi-bold qury">Balance :</span> 
                                    <span class="text-muted">${{ $wallet->amount ?? '0' }}</span>
                                </h6>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
          
        </div>
    </div>
</div>
<h3 class="mt-2"> Wallet History </h3>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th> Message</th>
                  <th> Amount </th>
                  <th> Status </th>
                  <th> Created Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($wallet->walletHistory()->orderBy('id','desc')->get() as $key => $data)
                  <tr>
                    <td> {{ $data->message ?? 'N/A' }} </td>
                    <td> @if(($data->payment_status == 'failed') || ($data->payment_status == 'add')) <span class="mdi mdi-plus text-success"></span> @else <span class="mdi mdi-minus text-danger"></span> @endif${{$data->amount}} </td>
                    <td> @if($data->payment_status == 'failed') <span class="badge badge-danger">Failed</span> @else <span class="badge badge-success">Success</span> @endif  </td>
                    <td> {{convertDate($data->created_at,'d M Y')}} </td>
                  </tr>
                @empty
                    <tr>
                      <td colspan="4" class="no-record"> <div class="col-12 text-center">No record found </div></td>
                    </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

