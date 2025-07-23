<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * functionName : index
     * createdDate  : 23-07-2024
     * purpose      : Get the dashboard detail for the admin
     */
    public function index(Request $request){
        $user = User::where('role_id','!=',1)->whereNull('deleted_at');

        $latest_requested_orders  = Order::where('order_type','online')
                            ->where('status', Order::ORDER_REQUESTED)
                            ->latest()->take(10)->get()
                            ->makeHidden(['service'])
                            ->each(function ($order) {
                               $order->service_name        = (collect($order->service->pluck('service'))->pluck('name'))->unique()->values()->toArray();
                            });
        
        $latest_orders  = Order::where('order_type','online')
                            ->whereNot('status', Order::ORDER_REQUESTED)
                            ->latest()->get()
                            ->makeHidden(['service'])
                            ->each(function ($order) {
                               $order->service_name        = (collect($order->service->pluck('service'))->pluck('name'))->unique()->values()->toArray();
                            });

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);
        
        $order = Order::where('order_type','online');
        $monthly_orders = $order->clone()->whereBetween('created_at', [$startDate->toDateString(), $endDate->toDateString()]);

        $monthly_order = [
            'total_order'               =>  $monthly_orders->clone()->count(),
            'in_progress_orders'        =>  $monthly_orders->clone()->whereNotIn('status',[Order::ORDER_COMPLETED,Order::ORDER_CANCELLED])->count(),
            'completed_orders'          =>  $monthly_orders->clone()->where('status',Order::ORDER_COMPLETED)->count(),
            'cancelled_orders'          =>  $monthly_orders->clone()->where('status',Order::ORDER_CANCELLED)->count()
        ];

        $total_order = [
            'total_order'               =>  $order->clone()->count(),
            'in_progress_orders'        =>  $order->clone()->whereNotIn('status',[Order::ORDER_COMPLETED,Order::ORDER_CANCELLED])->count(),
            'completed_orders'          =>  $order->clone()->where('status',Order::ORDER_COMPLETED)->count(),
            'cancelled_orders'          =>  $order->clone()->where('status',Order::ORDER_CANCELLED)->count()
        ];

        //  Get the details of the graph 
        $type = 'month';

        if ($request->filled('type')) {
            $type = $request->type;
        }
        
        $timeRange = Carbon::now();
    
        switch ($type) {
            case 'month':
                $months = collect(range(0, 5))->map(function ($i) {
                    return Carbon::now()->subMonths($i)->format('F');  
                });
                
                $earnings = Order::select(
                        DB::raw('SUM(total_amount) as total_amount'),
                        DB::raw('MONTHNAME(created_at) as `keys`')
                    )
                    ->where('status', Order::ORDER_COMPLETED)
                    ->where('created_at', '>=', Carbon::now()->subMonths(6))
                    ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                    ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                    ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
                    ->get();
                $earningsData = $months->mapWithKeys(function ($month) use ($earnings) {
                    $earning = $earnings->firstWhere('keys', $month);
                    return [$month => $earning ? $earning->total_amount : 0];  
                });
                // Pie chart details filteration
                $order_ids = Order::where('status',Order::ORDER_COMPLETED)
                            ->where('created_at', '>=', Carbon::now()->subMonths(6))
                            ->pluck('id')->toArray();
                
                $order_services = OrderService::whereIn('order_id', $order_ids)
                            ->select(DB::raw('COUNT(order_services.service_id) as count'), 'order_services.service_id', 'services.name as service_name')
                            ->join('services', 'order_services.service_id', '=', 'services.id')
                            ->groupBy('order_services.service_id', 'services.name')
                            ->pluck('count', 'service_name')
                            ->toArray();
            break;
        
            case 'year':
                $years = collect(range(Carbon::now()->year - 5, Carbon::now()->year));
        
                $earnings = Order::select(
                        DB::raw('SUM(total_amount) as total_amount'),
                        DB::raw('YEAR(created_at) as `keys`')
                    )
                    ->where('status', Order::ORDER_COMPLETED)
                    ->where('created_at', '>=', Carbon::now()->subYears(6))
                    ->groupBy(DB::raw('YEAR(created_at)'))
                    ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                    ->get();
        
                $earningsData = $years->mapWithKeys(function ($year) use ($earnings) {
                    $earning = $earnings->firstWhere('keys', $year);
                    return [$year => $earning ? $earning->total_amount : 0]; 
                });
                
                // Pie chart details filteration
                $order_ids = Order::where('status',Order::ORDER_COMPLETED)
                            ->where('created_at', '>=', Carbon::now()->subYears(6))
                            ->pluck('id')->toArray();
                
                $order_services = OrderService::whereIn('order_id', $order_ids)
                            ->select(DB::raw('COUNT(order_services.service_id) as count'), 'order_services.service_id', 'services.name as service_name')
                            ->join('services', 'order_services.service_id', '=', 'services.id')
                            ->groupBy('order_services.service_id', 'services.name')
                            ->pluck('count', 'service_name')
                            ->toArray();
            break;
        
            case 'week':
                $days = collect(range(0, 5))
                        ->map(function ($i) {
                            return Carbon::now()->subDays($i)->format('Y-m-d');  
                        }); 
            
                $earnings = Order::select(
                        DB::raw('SUM(total_amount) as total_amount'),
                        DB::raw('DATE(created_at) as day') 
                    )
                    ->where('status', Order::ORDER_COMPLETED)
                    ->where('created_at', '>=', Carbon::now()->subDays(6)) 
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->orderBy(DB::raw('DATE(created_at)'), 'asc')
                    ->get();
            
                $earningsData = $days->mapWithKeys(function ($day) use ($earnings) {
                    $earning = $earnings->firstWhere('day', $day);
                    return [$day => $earning ? $earning->total_amount : 0];
                });

                // Pie chart details filteration
                $order_ids = Order::where('status',Order::ORDER_COMPLETED)
                            ->where('created_at', '>=', Carbon::now()->subDays(6))
                            ->pluck('id')->toArray();
                
                $order_services = OrderService::whereIn('order_id', $order_ids)
                            ->select(DB::raw('COUNT(order_services.service_id) as count'), 'order_services.service_id', 'services.name as service_name')
                            ->join('services', 'order_services.service_id', '=', 'services.id')
                            ->groupBy('order_services.service_id', 'services.name')
                            ->pluck('count', 'service_name')
                            ->toArray();
            break;
                
        
            default:
                $months = collect(range(0, 5))->map(function ($i) {
                    return Carbon::now()->subMonths($i)->format('F');
                });
        
                $earnings = Order::select(
                        DB::raw('SUM(total_amount) as total_amount'),
                        DB::raw('MONTHNAME(created_at) as `keys`')
                    )
                    ->where('status', Order::ORDER_COMPLETED)
                    ->where('created_at', '>=', Carbon::now()->subMonths(6))
                    ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                    ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                    ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
                    ->get();
        
                $earningsData = $months->mapWithKeys(function ($month) use ($earnings) {
                    $earning = $earnings->firstWhere('keys', $month);
                    return [$month => $earning ? $earning->total_amount : 0]; 
                });

                // Pie chart details filteration
                $order_ids = Order::where('status',Order::ORDER_COMPLETED)
                            ->where('created_at', '>=', Carbon::now()->subMonths(6))
                            ->pluck('id')->toArray();
                
                $order_services = OrderService::whereIn('order_id', $order_ids)
                            ->select(DB::raw('COUNT(order_services.service_id) as count'), 'order_services.service_id', 'services.name as service_name')
                            ->join('services', 'order_services.service_id', '=', 'services.id')
                            ->groupBy('order_services.service_id', 'services.name')
                            ->pluck('count', 'service_name')
                            ->toArray();
            break;
        }
            
        $keys = array_keys($earningsData->toArray());
        $totalEarnings =array_values($earningsData->toArray());
        // dd($order_services);
        $service_keys = array_keys($order_services);
        $service_values =array_values($order_services);

        // Pie chart response data
        

        $services = OrderService::whereIn('order_id',$order_ids);
        $responseData =[
            'latest_requested_orders'       => $latest_requested_orders,
            'latest_orders'                 => $latest_orders,
            'total_registered_user'         => $user->clone()->count(),
            'total_active_user'             => $user->clone()->where('status',1)->count(),
            'monthly_order'                 => $monthly_order,
            'total_order'                   => $total_order,    
            
            // revenue graph details  
            'keys'                          => json_encode($keys),
            'total_earnings'                => json_encode($totalEarnings),

            'service_keys'                  => json_encode($service_keys),
            'service_values'                => json_encode($service_values),

        ];
        return view("admin.dashboard",compact('responseData'));
    }
    /**End method index**/


    /**
     * functionName : getTrashedList
     * createdDate  : 18-11-2024
     * purpose      : Get the list for of all the trashed user
    */
    public function getTrashedList(Request $request){
        try{
            $users = User::where("role_id",2)->onlyTrashed()
            ->when($request->filled('search_keyword'),function($query) use($request){
                $query->where(function($query) use($request){
                    $query->where('first_name','like',"%$request->search_keyword%")
                        ->orWhere('last_name','like',"%$request->search_keyword%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$request->search_keyword}%"])
                        ->orWhere('email','like',"%$request->search_keyword%");
                });
            })
            ->orderBy("deleted_at","desc")->paginate(10);
            return view("admin.trashed-list",compact("users"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getTrashedList**/

    /**
     * functionName : restore
     * createdDate  : 18-11-2024
     * purpose      : Delete the user by id
    */
    public function restore($id){
        try{
            User::where('id',$id)->restore();     
            return response()->json(["status" => "success","message" => "User ".config('constants.SUCCESS.RESTORE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method restore**/
}
