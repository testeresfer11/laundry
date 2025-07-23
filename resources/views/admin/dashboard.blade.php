@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('breadcrum')
<div class="page-header">
    <h3 class="page-title"> Dashboard </h3>
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">dashboard</li>
    </ol>
    </nav>
</div>
@endsection
@section('content')
<div>
    @can('dashboard-monthly-order-cards')
        <h4>Monthly Orders Overview</h4>
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'total','record'=>'month']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['monthly_order']['total_order'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-primary">
                                        <span class="mdi mdi-border-all icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Total Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'in-progress','record'=>'month']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['monthly_order']['in_progress_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-success">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Orders In Progress</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'completed','record'=>'month']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['monthly_order']['completed_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-info">
                                        <span class="mdi mdi-check-circle-outline icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Completed Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'cancelled','record'=>'month']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['monthly_order']['cancelled_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-danger">
                                        <span class="mdi mdi-close-circle-outline icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Cancelled Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            
        </div>
    @endcan

    @can('dashboard-total-order-cards')
        <h4>Total Orders Overview</h4>
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'total']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['total_order']['total_order'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-primary">
                                        <span class="mdi mdi-border-all icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Total Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'in-progress']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['total_order']['in_progress_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-success">
                                        <span class="mdi mdi-arrow-top-right icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Orders In Progress</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'completed']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['total_order']['completed_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-info">
                                        <span class="mdi mdi-check-circle-outline icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Completed Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <a href="{{ route('admin.order.list',['type'=>'cancelled']) }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{$responseData['total_order']['cancelled_orders'] ?? 0}}</h3>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="icon icon-box-danger">
                                        <span class="mdi mdi-close-circle-outline icon-item"></span>
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal">Cancelled Orders</h6>
                        </div>
                    </a>
                </div>
            </div>
            
        </div>
    @endcan

    @can('dashboard-revenue-cards')
        <h4>Revenue Overview</h4>
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">120</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-primary">
                                    <span class="mdi mdi-currency-usd icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Revenue Today</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">45</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success">
                                    <span class="mdi mdi-currency-usd icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Revenue This Week</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">65</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-info">
                                    <span class="mdi mdi-currency-usd icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Revenue This Month</h6>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('dashboard-graph')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">Graphical Representation</h4>
            <!-- Buttons for toggling views -->
            <div class="btn-group" role="group" aria-label="Chart View">
                <button type="button" class="btn btnRevenue {{request()->filled('type') && request()->type == 'week' ? 'btn-primary active ' : 'btn-outline-primary'}}" id="btnWeekly" data-value="week">Weekly</button>
                <button type="button" class="btn btnRevenue {{request()->filled('type') && request()->type == 'month' ? 'btn-primary active ' : 'btn-outline-primary'}} {{ !request()->filled('type') ? 'btn-primary active': 'btn-outline-primary' }}" id="btnMonthly" data-value="month">Monthly</button>
                <button type="button" class="btn btnRevenue {{request()->filled('type') && request()->type == 'year' ? 'btn-primary active ' : 'btn-outline-primary'}}" id="btnYearly" data-value="year">Yearly</button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title">Revenue chart</h4>
                        </div>
                        <canvas id="barChart" style="height:250px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
                @if($responseData['service_keys'] == '[]')
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Service chart</h4>
                        <div id="noDataOverlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 20px; ">
                            No record
                        </div>
                    </div>
                </div>
                
                @else
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Service chart</h4>
                            <canvas id="pieChart" style="height:250px"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endcan

    @can('dashboard-latest-requested-orders')
        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h4>Latest Requested Orders</h4>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{route('admin.storeOrder.create')}}">
                    <button type="button" class="btn default-btn btn-md">
                        <span class="menu-icon">+ Create Order</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 grid-margin stretch-card">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Order Id </th>
                                    <th> Order By </th>
                                    <th> Services </th>
                                    <th> Order At </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($responseData['latest_requested_orders'] as $order)
                                    <tr data-id="{{$order->id}}">
                                        <td>{{$order->order_id}}</td>
                                        <td>{{$order->user ? $order->user->full_name : 'N/A'}}</td>
                                        <td> {{ implode(', ', $order->service_name)}}</td>
                                        <td>{{convertDate($order->created_at)}}</td>
                                        <td> 
                                            <span class="menu-icon">
                                                <a href="{{route('admin.order.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
        
                                            @if(orderStatusCheck($order->id,'Requested	'))
                                                <span class="menu-icon">
                                                    <a href="{{route('admin.order.edit',['id' => $order->id])}}" title="Edit" class="text-success"><i class="mdi mdi-pencil"></i></a>
                                                </span>&nbsp;&nbsp;&nbsp;
                                            @endif
        
                                            <span class="menu-icon">
                                                <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}" title="Download Invoice" class=""><i class="mdi mdi-download" style="font-size: 20px;"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
        
                                            <!-- Accept Button -->
                                            <button 
                                                class="btn btn-success btn-sm  orderStatusChange" data-id="{{$order->id}}" 
                                                data-value="Accepted"
                                                title="Accept Order">
                                                Accept
                                            </button>&nbsp;
        
                                            <!-- Reject Button -->
                                            <button 
                                                class="btn btn-danger btn-sm reject" 
                                                data-id="{{$order->id}}" data-value="Cancel" data-title="dashboard-reject">
                                                Cancel
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="no-record"> 
                                            <div class="col-12 text-center">No record found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @can('dashboard-latest-orders')
        <div class="row align-items-center mb-4">
            <div class="col-sm-6">
                <h4>Latest Orders</h4>
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ route('admin.order.list',['type'=>'in-progress','record'=>'month']) }}">
                    <button type="button" class="btn default-btn btn-md">
                        <span class="menu-icon">View All</span>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 grid-margin stretch-card">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th> Order Id </th>
                                    <th> Order By </th>
                                    <th> Services </th>
                                    <th> Status </th>
                                    <th> Updated At </th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($responseData['latest_orders']->take(10) as $order)
                                    <tr data-id="{{$order->id}}">
                                        <td>{{$order->order_id}}</td>
                                        <td>{{$order->user ? $order->user->full_name : 'N/A'}}</td>
                                        <td> {{ implode(', ', $order->service_name)}}</td>
                                        <td> {{ ucwords(str_replace('-', ' ', $order->status))}} </td>
                                        <td>{{convertDate($order->updated_at)}}</td>
                                        <td> 
                                            <span class="menu-icon">
                                                <a href="{{route('admin.order.view',['id' => $order->id])}}" title="View" class="text-primary"><i class="mdi mdi-eye"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
        
                                            <span class="menu-icon">
                                                <a href="{{route('admin.storeOrder.invoice.download',['id' => $order->id])}}" title="Download Invoice" class=""><i class="mdi mdi-download" style="font-size: 20px;"></i></a>
                                            </span>&nbsp;&nbsp;&nbsp;
        
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="no-record"> 
                                            <div class="col-12 text-center">No record found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    @endcan
</div>
@include('admin.order.popup')
@endsection
@section('scripts')
@include('admin.order.orderjs')
<script src="{{asset('admin/js/dashboard.js')}}"></script>

<script>

    $(".btnRevenue").on('click',function() {
        var type = $(this).data('value');
        var currentUrl = window.location.href.split('?')[0];
        window.location.href = currentUrl + "?type="+type;
    });

    function getRandomColor() {
        var standardColors = ['#000000','#808080','#B0B0B0','#D3D3D3','#FFFFFF','#A9A9A9','linear-gradient(#000000, #434343)',
            'linear-gradient(#808080, #D3D3D3)','#C0C0C0','#696969'
        ];

        var randomIndex = Math.floor(Math.random() * standardColors.length);
        return standardColors[randomIndex];
    }

    var randomColors = barChartRandomColor= [];
    <?php 
        $serviceValues = json_decode($responseData['service_values']); 
        foreach ($serviceValues as $value) {
            echo "randomColors.push(getRandomColor());\n";
        }

        $chartValues = json_decode($responseData['keys']); 
        foreach ($chartValues as $value) {
            echo "barChartRandomColor.push(getRandomColor());\n";
        }

    ?>

    var data = {
        labels: <?php echo $responseData['keys']; ?>,
        datasets: [{
        label: '$ revenue',
        data: <?php echo $responseData['total_earnings']; ?>,
        backgroundColor: barChartRandomColor,
        borderWidth: 1,
        fill: false
        }]
    };

    var options = {
        scales: {
        yAxes: [{
            ticks: {
            beginAtZero: true
            },
            gridLines: {
            color: "rgba(204, 204, 204,0.1)"
            }
        }],
        xAxes: [{
            gridLines: {
            color: "rgba(204, 204, 204,0.1)"
            }
        }]
        },
        legend: {
        display: false
        },
        elements: {
        point: {
            radius: 0
        }
        }
    };

    var barChartCanvas = $("#barChart").get(0).getContext("2d");

    var barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: data,
        options: options
    });


    var doughnutPieData = {
        datasets: [{
            data: <?php echo $responseData['service_values']; ?>,
            backgroundColor: randomColors
        }],

        labels: <?php echo $responseData['service_keys']; ?>
    };

    var doughnutPieOptions = {
        responsive: true,
        animation: {
        animateScale: true,
        animateRotate: true
        }
    };

    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: doughnutPieData,
      options: doughnutPieOptions
    });


</script>
@endsection

