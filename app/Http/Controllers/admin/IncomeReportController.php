<?php

namespace App\Http\Controllers\admin;

use App\Exports\IncomeReport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class IncomeReportController extends Controller
{
    /**
     * functionName : getList
     * createdDate  : 25-11-2024
     * purpose      : Get the list of order for income order
    */
    public function getList(Request $request){
        try{
            $fromDate = null;
            $toDate = null;
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $fromDate = Carbon::parse($request->from_date);
                $toDate = Carbon::parse($request->to_date);
                if ($fromDate->gt($toDate)) {
                    $temp = $fromDate;
                    $fromDate = $toDate;
                    $toDate = $temp;
                }
            }

            $orders = Order::where('status',Order::ORDER_DELIVERED)
                    ->when($fromDate && $toDate, function($query) use ($fromDate, $toDate) {
                        $query->whereBetween('created_at', [$fromDate, $toDate]);
                    })
                    ->when($request->filled('search_keyword'), function ($query) use ($request) {
                        $searchKeyword = "%" . $request->search_keyword . "%";
                        $query->where(function ($query) use ($searchKeyword) {
                            $query->where('order_id', 'like', $searchKeyword)
                                ->orWhere('total_amount', 'like', $searchKeyword)
                                ->orWhereHas('user', function ($query) use ($searchKeyword) {
                                    $query->where('first_name', 'like', $searchKeyword)
                                        ->orWhere('last_name', 'like', $searchKeyword)
                                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchKeyword]);
                                });
                        });
                    })->orderBy("id", "desc")
                    ->paginate(10);
                
            return view("admin.income.list",compact("orders"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : export
     * createdDate  : 16-07-2024
     * purpose      : export the locker equipment
    */
    public function export(Request $request)
    {
        $fromDate = null;
        $toDate = null;
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = Carbon::parse($request->from_date);
            $toDate = Carbon::parse($request->to_date);
            if ($fromDate->gt($toDate)) {
                $temp = $fromDate;
                $fromDate = $toDate;
                $toDate = $temp;
            }
        }

        $query = Order::where('status',Order::ORDER_DELIVERED)
                ->when($fromDate && $toDate, function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                })
                ->when($request->filled('search_keyword'), function ($query) use ($request) {
                    $searchKeyword = "%" . $request->search_keyword . "%";
                    $query->where(function ($query) use ($searchKeyword) {
                        $query->where('order_id', 'like', $searchKeyword)
                            ->orWhere('total_amount', 'like', $searchKeyword)
                            ->orWhereHas('user', function ($query) use ($searchKeyword) {
                                $query->where('first_name', 'like', $searchKeyword)
                                    ->orWhere('last_name', 'like', $searchKeyword)
                                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchKeyword]);
                            });
                    });
                })->orderBy("id", "desc")
                ->select(
                    'order_id',
                    'user_id',
                    'order_type',
                    'total_amount',
                    'pickup_address',
                    'pickup_driver_id',
                    'pickup_date',
                    'pickup_time',
                    'delivery_address',
                    'delivery_driver_id',
                    'delivery_date',
                    'delivery_time',
                    'created_at',
                    'updated_at'
                );


                $orders = $query->get()->map(function ($order) {
                    $pickup = is_string($order->pickup_address)
                        ? json_decode($order->pickup_address, true)
                        : $order->pickup_address;
                
                    $delivery = is_string($order->delivery_address)
                        ? json_decode($order->delivery_address, true)
                        : $order->delivery_address;
                
                    // Remove unwanted fields from pickup address
                    if (is_array($pickup)) {
                        unset($pickup['lat'], $pickup['long']);
                        $order->pickup_address = implode(', ', array_filter($pickup));
                    }
                
                    // Optionally clean delivery address similarly (or leave as is)
                    if (is_array($delivery)) {
                        unset($delivery['lat'], $delivery['long']);
                        $order->delivery_address = implode(', ', array_filter($delivery));
                    }

                    return $order;
                });
            
        return Excel::download(new IncomeReport($orders), 'income-report.csv');
    }
    /**End method export**/

    
}
