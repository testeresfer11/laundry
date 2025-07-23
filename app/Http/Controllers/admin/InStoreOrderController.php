<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{Order,OrderService,OrderTax,Payment,Role,Service,Tax,User,UserDetail};
use Illuminate\Support\Facades\{Hash,Validator};
use App\Traits\SendResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InStoreOrderController extends Controller
{
    use SendResponseTrait;

    /**
     * functionName : getList
     * createdDate  : 17-09-2024
     * purpose      : Get the list of in store orders
    */
    public function getList(Request $request){
        try{
            $orders = Order::where('order_type','store')
                    ->when($request->filled('search_keyword'), function ($query) use ($request) {
                        $searchKeyword = "%" . $request->search_keyword . "%"; // To avoid repeating % signs
                        $query->where(function ($query) use ($searchKeyword) {
                            $query->where('order_id', 'like', $searchKeyword)
                                ->orWhere('status', 'like', $searchKeyword)
                                ->orWhereHas('user', function ($query) use ($searchKeyword) {
                                    $query->where('first_name', 'like', $searchKeyword)
                                        ->orWhere('last_name', 'like', $searchKeyword)
                                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchKeyword]);
                                });
                        });
                    })->orderBy("created_at","desc")->paginate(10);

            return view("admin.storeOrder.list",compact("orders"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : create
     * createdDate  : 17-09-2024
     * purpose      : create the in store order
    */
    public function create(Request $request){
        try{
            if($request->isMethod('get')){
                $users = User::where('role_id',2)->where('status',1)->get();
                $services = Service::where('status',1)->get();
                return view("admin.storeOrder.add",compact('users','services'));
            }else{
                $rules = [
                    'user_id'         => 'required',
                    'service_variant' => 'required',
                ];
                if($request->user_id == "add_new_user"){
                    array_merge($rules,[
                        'first_name'      => 'required|string|max:255',
                        'last_name'       => 'required|string|max:255',
                        'email'           => 'required|email:rfc,dns|unique:users,email',
                    ]);
                }
                $validator = Validator::make($request->all(), $rules);
            
                if ($validator->fails()) {
                    return redirect()->back()->with("error", $validator->errors()->first());
                }
                if($request->user_id == "add_new_user"){
                    $password = generateRandomString();
                    $role = Role::where('name' , config('constants.ROLES.CUSTOMER'))->first();
                    $user = User::Create([
                        'role_id'           => $role->id,
                        'first_name'        => $request->first_name,
                        'last_name'         => $request->last_name,
                        'email'             => $request->email,
                        'password'          => Hash::make($password),
                        'is_email_verified' => 1,
                        'email_verified_at' => date('Y-m-d H:i:s'),
                    ]);
                    UserDetail::create([
                        'user_id'               => $user->id,
                        'phone_number'          => $request->phone_number ? $request->phone_number : null,
                    ]);
                    $user_id = $user->id;
                }else{
                    $user_id = $request->user_id;
                }

                $service_variant = json_decode($request->service_variant);
                $prices = array_map('floatval', array_column($service_variant, 'price'));
                $actualPrice = array_sum($prices);

                $taxes = Tax::where('status',1)->get();

                $taxs_amount = 0;
                foreach($taxes as $tax){
                    $taxs_amount += ( $actualPrice * $tax->percentage ) / 100;
                }
                
                $totalPrice = $actualPrice + $taxs_amount;

                $order = Order::create([
                    'user_id'       => $user_id,
                    'status'        => Order::ORDER_ACCEPTED,
                    'total_amount'  => $totalPrice,
                    'pickup_date'   => date('Y-m-d'),
                    'order_type'    => 'store'
                ]);

                foreach($service_variant as $value){
                    OrderService::create([
                        'order_id'      => $order->id,
                        'service_id'    => $value->service_id,
                        'variant_id'    => $value->variant_id,
                        'amount'        => $value->amount,
                        'qty'           => $value->qty,
                    ]);
                }

                foreach($taxes as $tax){
                    OrderTax::create([
                        'user_id'       => authId(),
                        'order_id'      => $order->id,
                        'title'         => $tax->label,
                        'rate'          => $tax->percentage,
                        'amount'        => ( $actualPrice * $tax->percentage ) / 100,
                    ]);
                }

                OrderTax::create([
                    'user_id'       => authId(),
                    'order_id'      => $order->id,
                    'title'         => 'Delivery Fee',
                    'amount'        => 0,
                    'rate'          => 0,
                ]);

                orderHistory($order->id,Order::ORDER_ACCEPTED);

                return redirect()->route('admin.storeOrder.list')->with('success','Order '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method create**/


    /**
     * functionName : view
     * createdDate  : 05-08-2024
     * purpose      : view the store order detail
    */
    public function view($id){
        try{
            $order = Order::find($id);
            return view("admin.storeOrder.view",compact('order'));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/

    /**
     * functionName : paid
     * createdDate  : 16-09-2024
     * purpose      : Paid for the order
    */
    public function paid(Request $request){
        try{
            $rules = [
                'order_id'              => 'required|exists:orders,id',
                'payment_method'        => 'required|in:card,cash',
                'transaction_id'        => 'required_if:payment_method,card',
            ];
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }
            $order = Order::find($request->order_id);

            Payment::create([
                'user_id'           => $order->user_id,
                'order_id'          => $order->id,
                'payment_type'      => $request->payment_method,
                'payment_id'        => $request->payment_method == "card" ? $request->transaction_id : null ,
                'amount'            => $order->total_amount,
                'status'            => 'Success'
            ]);

            orderHistory($request->order_id,Order::ORDER_PAID);
            
            return $this->apiResponse('success',200,'Order '.config('constants.SUCCESS.PAID_DONE'),route('admin.storeOrder.list'));
           
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method paid**/

    /**
     * functionName : edit
     * createdDate  : 23-09-2024
     * purpose      : edit the order
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $order = Order::find($id);
                return view("admin.storeOrder.edit",compact('order'));
            }else{
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/

    public function downloadInvoice($id)
    {
        $order = Order::find($id);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        
        return $pdf->download('invoice.pdf');
    }
}
