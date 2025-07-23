<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{ConfigSetting, CustomerAddress, Order, OrderDeclineReason, OrderService, OrderTax, Service,User,ServiceVariant, Tax};
use App\Traits\SendResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class OrderManagementController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 05-08-2024
     * purpose      : Get the list of order
    */
    public function getList(Request $request, $type){
        try{
            $status = '';
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);
            $orders = Order::where('order_type', 'online')
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
                    })
                    ->when(($request->filled('record') && ($request->record == 'month')),function($query) use($startDate,$endDate){
                        $query->whereBetween('created_at', [$startDate->toDateString(), $endDate->toDateString()]);
                    });
        
            switch($type){
                case 'requested':
                    $orders = $orders->where('status',Order::ORDER_REQUESTED);
                break;
                case 'all':
                    $orders = $orders->whereNotIn('status',[Order::ORDER_REQUESTED,Order::ORDER_CANCELLED]);
                break;
                case 'completed':
                    $orders = $orders->where('status',Order::ORDER_COMPLETED);
                break;
                case 'in-progress':
                    $orders = $orders->whereNotIn('status',[Order::ORDER_COMPLETED,Order::ORDER_CANCELLED]);
                break;
                case 'cancelled':
                    $orders = $orders->whereIn('status',[Order::ORDER_CANCELLED,Order::ORDER_REJECTED]);
                break;
            }
            $orders = $orders->orderBy("id","desc")->paginate(10);

            return view("admin.order.list",compact("orders"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : create
     * createdDate  : 05-08-2024
     * purpose      : create the order
    */
    public function create(Request $request){
        try{
            if($request->isMethod('get')){
                $users = User::where('role_id',2)->where('status',1)->get();
                $services = Service::where('status',1)->get();
                return view("admin.order.add",compact('users','services'));
            }else{
                $rules = [
                    'user_id'               => 'required|exists:users,id',
                    'pickup_id'             => 'required|exists:customer_addresses,id',
                    'delivery_id'           => 'required|exists:customer_addresses,id',
                    'service_variant'       => 'required',
                    'pickup_date'           => 'required'
                ];
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }


                $service_variant = json_decode($request->service_variant);
                $prices = array_map('floatval', array_column($service_variant, 'amount'));
                $actualPrice = array_sum($prices);

                $pickup_address = CustomerAddress::find($request->pickup_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);
                $delivery_address = CustomerAddress::find($request->delivery_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);

                $order = Order::create([
                    'user_id'       => $request->user_id,
                    'status'        => Order::ORDER_ACCEPTED,
                    'total_amount'  => $actualPrice,
                    'pickup_address' => json_encode($pickup_address),
                    'delivery_address'=> json_encode($delivery_address),
                    'pickup_date'     => $request->pickup_date
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

                $taxes = Tax::where('status',1)->get();
               
                $detail = ConfigSetting::where('type','delivery-cost')->pluck('value','key');
                 
                if($actualPrice > ($detail['FREE_DELIVERY'] ?? 0)){
                    $delivery_fee = 0;
                }else{
                    $pickup_address = CustomerAddress::find($request->pickup_id);
                    $delivery_address = CustomerAddress::find($request->delivery_id);
                    
                    if($pickup_address && $delivery_address && $pickup_address->lat && $pickup_address->long && $delivery_address->lat && $delivery_address->long){
                        $km =  haversineGreatCircleDistance($pickup_address->lat ,$pickup_address->long , $delivery_address->lat ,$delivery_address->long);
                         $intdetail = isset($detail['DELIVERY_CHARGE']) ? (int) $detail['DELIVERY_CHARGE'] : 1;
                        // dd(gettype($km));
                        $delivery_fee = (int)$km * ($intdetail ?? 1);
                       
                    }else{
                        $delivery_fee = 0;
                    }
                   
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
                    'amount'        => $delivery_fee,
                    'rate'          => 0,
                ]);
                

                orderHistory($order->id,Order::ORDER_REQUESTED);

                orderHistory($order->id,Order::ORDER_ACCEPTED);

                return redirect()->route('admin.order.list',['type' => 'all'])->with('success','Order '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

     /**
     * functionName : edit
     * createdDate  : 19-09-2024
     * purpose      : edit the order
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $order = Order::find($id);
                return view("admin.order.edit",compact('order'));
            }else{
                $rules = [
                    'user_id'               => 'required|exists:users,id',
                    'pickup_id'             => 'required|exists:customer_addresses,id',
                    'delivery_id'           => 'required|exists:customer_addresses,id',
                    'service_variant'       => 'required',
                ];
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $service_variant = json_decode($request->service_variant);
                $prices = array_map('floatval', array_column($service_variant, 'price'));
                $totalPrice = array_sum($prices);

                $pickup_address = CustomerAddress::find($request->pickup_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);
                $delivery_address = CustomerAddress::find($request->delivery_id)->makeHidden(['status', 'default', 'type', 'id', 'created_at', 'updated_at','deleted_at','user_id']);

                $order = Order::create([
                    'user_id'       => $request->user_id,
                    'status'        => Order::ORDER_ACCEPTED,
                    'total_amount'  => $totalPrice,
                    'pickup_address' => json_encode($pickup_address),
                    'delivery_address'=> json_encode($delivery_address)
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
                orderHistory($order->id,Order::ORDER_REQUESTED);

                orderHistory($order->id,Order::ORDER_ACCEPTED);

                return redirect()->route('admin.order.list',['type' => 'all'])->with('success','Order '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : getServiceVariant
     * createdDate  : 12-08-2024
     * purpose      : get the variants of service
    */
    public function getServiceVariant($serviceId){
        try{
            
            $html ='';
            $html .="<select name='variant_id' class='form-control variant-list-select variant_id'>
                  <option value=''>Select Variant</option>";
            $serviceVariants = ServiceVariant::where('service_id',$serviceId)->get();
            foreach ($serviceVariants as $value){
                $html .="<option value=".$value->variant_id.">".($value->variant ? $value->variant->name : 'N/A')."</option>";
            }
            $html .="</select>";
            return $this->apiResponse('success',200, __('messages.Service variant list') .' '. __('messages.SUCCESS.FETCH_DONE'),$html);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method getServiceVariant**/

    /**
     * functionName : getServiceVariantPrice
     * createdDate  : 12-08-2024
     * purpose      : get the variants of service
    */
    public function getServiceVariantPrice($serviceId, $variantId){
        try{
            $service_variant = ServiceVariant::where('service_id',$serviceId)->where('variant_id',$variantId)->first();
            return $this->apiResponse('success',200, __('messages.Service variant list') .' '. __('messages.SUCCESS.FETCH_DONE'), $service_variant ?  $service_variant->price : 'N/A');
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method getServiceVariant**/

    /**
     * functionName : getList
     * createdDate  : 05-08-2024
     * purpose      : Get the list of order
    */
    public function view($id){
        try{
            $order = Order::find($id);
            return view("admin.order.view",compact('order'));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : changeStatus
     * createdDate  : 16-09-2024
     * purpose      : status change the order
    */
    public function changeStatus($id,$status){
        try{
            $order = Order::find($id);
            $old_status = $order->status;
            $order_id = $order->order_id;
            $amount = $order->total_amount;
            $created_at = convertDate($order->created_at);

            orderHistory($id,$status);
            
            $order = Order::find($id);
            $title = $body ='';

            switch($status){
                case Order::ORDER_REQUESTED:
                    $title = __('messages.Order Requested');
                    $body   = __('messages.Order has been requested successfully.');
                    break;
                    
                case Order::ORDER_ACCEPTED:
                    $title = __('messages.Order Accepted');
                    $body   = __('messages.Order has been accepted successfully.');
                    $template = $this->getTemplateByName('order_accept_approve');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_accept_approve', $template->id);
                        $this->mailSend($emailData);
                    }
                    break;
                    
                case Order::ORDER_CANCELLED:
                    $title = __('messages.Order Cancelled');
                    $body   = __('messages.Order has been cancelled.');
                    $template = $this->getTemplateByName('order_reject_cancel');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}','{{$reason}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at,$order->reason];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_reject_cancel', $template->id);
                        $this->mailSend($emailData);
                    }

                    
                    break;
                    
               
                    
                case Order::ORDER_APPROVED:
                    $title = __('messages.Order Approved');
                    $body   = __('messages.Order has been approved.');
                    $template = $this->getTemplateByName('order_accept_approve');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_accept_approve', $template->id);
                        $this->mailSend($emailData);
                    }
                break;
                    
                case Order::ORDER_PAID:
                    $title = __('messages.Order Paid');
                    $body   = __('messages.Order has been paid successfully.');
                break;
                
                case Order::ORDER_IN_PROGRESS:
                    $title = __('messages.Order In Progress');
                    $body   = __('messages.Your order is in progress.');
                    $template = $this->getTemplateByName('order_in_progress');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_in_progress', $template->id);
                        $this->mailSend($emailData);
                    }
                break;

                case Order::ORDER_READY:
                    $title = __('messages.Order Ready');
                    $body   = __('messages.Your order is ready for delivery.');
                    $template = $this->getTemplateByName('order_ready');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_ready', $template->id);
                        $this->mailSend($emailData);
                    }
                break;
                    
                case Order::ORDER_DELIVERED:
                    $title = __('messages.Order Delivered');
                    $body   = __('messages.Order has been delivered successfully.');
                    $template = $this->getTemplateByName('order_delivered');
                    if( $template ) { 
                        $stringToReplace    = ['{{$name}}','{{$old_status}}','{{$new_status}}','{{$order_id}}','{{$amount}}','{{$services}}','{{$created_at}}'];
                        $stringReplaceWith  = [userNameById( $order->user_id),$old_status,$status,$order_id,$amount,implode(',',$order->service_name),$created_at];
                        $newval             = str_replace($stringToReplace, $stringReplaceWith, $template->template);
                        $newsubject         = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
                        $emailData          = $this->mailData($order->user->email, $newsubject, $newval, 'order_delivered', $template->id);
                        $this->mailSend($emailData);
                    }
                break;
                    
                case Order::ORDER_COMPLETED:
                    $title = __('messages.Order Completed');
                    $body   = __('messages.Order has been completed successfully.');
                break;
                
                    
                case Order::PICKUP:
                    $title = __('messages.Pickup');
                    $body   = _('messages.Order is being picked up.');
                    break;
                    
                case Order::RECEIVED:
                    $title = __('messages.Order Received');
                    $body   = __('messages.Order has been received successfully.');
                    break;
                
                default:
                    $title = __('messages.Unknown Status');
                    $body   = __('messages.Status is unknown.');
                break;
            }
            
            

            $this->sendPushNotification( $order->user_id,$title ,  $body);
            
            return $this->apiResponse('success',200, __('messages.Order status') .' '. __('messages.SUCCESS.CHANGED_DONE'));
           
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : reject
     * createdDate  : 16-09-2024
     * purpose      : reject the order
    */
    public function reject(Request $request){
        try{
            $rules = [
                'order_id'              => 'required|exists:orders,id',
                'reason'                => 'required',
                'order_status'          => 'required',
                'image'                 => 'nullable|mimes:jpeg,jpg,png,gif|max:20480',
            ];

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }
            $order = Order::find($request->order_id);

            $ImgName = Null;
            $ImgUrl  = Null;
            if ($request->hasFile('image')) {
                $ImgName = uploadFile($request->file('image'),'images/');
                $ImgUrl  = url('storage/'.$ImgName);
            }

            OrderDeclineReason::updateOrCreate(['order_id' => $request->order_id],[
                'reason' => $request->reason,
                'image' => $ImgName]);

            // Send notification order rejected
            $this->sendPushNotification( $order->user_id, __('messages.Order Rejected'), __('messages.Order has been rejected by Admin due to') .' '. $request->reason . '.',null,null, $ImgUrl);

            orderHistory($request->order_id,$request->order_status);

            
            return $this->apiResponse('success',200, __('messages.Order') .' '. __('messages.SUCCESS.CANCEL_DONE'),route('admin.order.list',['type'=>'cancelled']));
           
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method reject**/

    /**
     * functionName : assignDriver
     * createdDate  : 18-09-2024
     * purpose      : Assign the driver
    */
    public function assignDriver(Request $request){
        try{
            $rules = [
                'order_id' => 'required|exists:orders,id',
                'driver_id' => 'required|exists:users,id',
            ];
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return $this->apiResponse('error', 422, $validator->errors()->first());
            }
    
            $order = Order::find($request->order_id);
    
            // Check and assign pickup driver
            if ($order->pickup_driver_id == null && $order->status == Order::ORDER_ACCEPTED) {
                Order::where('id', $order->id)->update(['pickup_driver_id' => $request->driver_id]);
                $order = Order::find($order->id);
                orderHistory($order->id, Order::ASSIGN_DRIVER);
    
                // Send email and push notifications for pickup driver
                $template = $this->getTemplateByName('assign_driver');
                if ($template) {
                    $this->sendAssignmentEmail($order, $template, 'pickup');
                }
    
                // Send push notification to the pickup driver
                $this->sendPushNotification($order->pickup_driver_id, __('messages.New Order Assigned'), __('messages.You have been assigned to pick up an order') .' '. $order->order_id . '.');
    
                // Send push notification to the user
                $this->sendPushNotification($order->user_id, __('messages.Driver Assigned for Pickup'), __('messages.Your order has been assigned to') .' '. userNameById($order->pickup_driver_id) .' '. __('messages.for pickup.'));
    
            } elseif ($order->pickup_driver_id !== null && $order->status == Order::ASSIGN_DRIVER) {
                Order::where('id', $order->id)->update(['pickup_driver_id' => $request->driver_id]);
                $order = Order::find($order->id);
    
                // Send email and push notifications for pickup driver reassignment
                $template = $this->getTemplateByName('assign_driver');
                if ($template) {
                    $this->sendAssignmentEmail($order, $template, 'pickup');
                }
    
                // Send push notification to the pickup driver
                $this->sendPushNotification($order->pickup_driver_id, __('messages.New Order Assigned'), __('messages.You have been assigned to pick up an order') .' '. $order->order_id . '.');
    
                // Send push notification to the user
                $this->sendPushNotification($order->user_id, __('messages.Change Assigned Driver for Pickup'), __('messages.Your pickup driver has been changed to') .' '. userNameById($order->pickup_driver_id));
            }
    
            // Check and assign delivery driver
            if ($order->delivery_driver_id == null && ($order->status == Order::ORDER_READY || $order->status == Order::ORDER_REJECTED )) {
                Order::where('id', $order->id)->update(['delivery_driver_id' => $request->driver_id]);
                $order = Order::find($order->id);
                orderHistory($order->id, Order::ASSIGN_DELIVERY_DRIVER);
    
                // Send email and push notifications for delivery driver
                $template = $this->getTemplateByName('assign_driver');
                if ($template) {
                    $this->sendAssignmentEmail($order, $template, 'deliver');
                }
    
                // Send push notification to the delivery driver
                $this->sendPushNotification($order->delivery_driver_id, __('messages.New Delivery Order Assigned'), __('messages.You have been assigned to deliver up an order') .' '. $order->order_id . '.');
    
                // Send push notification to the user
                $this->sendPushNotification($order->user_id, __('messages.Driver Assigned for Delivery'), __('messages.Your order has been assigned to') .' '. userNameById($order->delivery_driver_id) .' '. __('messages.for delivery.'));
            } elseif ($order->delivery_driver_id != null && $order->status == Order::ASSIGN_DELIVERY_DRIVER ) {
                Order::where('id', $order->id)->update(['delivery_driver_id' => $request->driver_id]);
                $order = Order::find($order->id);
    
                // Send email and push notifications for delivery driver reassignment
                $template = $this->getTemplateByName('assign_driver');
                if ($template) {
                    $this->sendAssignmentEmail($order, $template, 'deliver');
                }
    
                // Send push notification to the delivery driver
                $this->sendPushNotification($order->delivery_driver_id, __('messages.New Delivery Order Assigned'), __('messages.You have been assigned to deliver up an order') .' '. $order->order_id . '.');
    
                // Send push notification to the user
                $this->sendPushNotification($order->user_id, __('messages.Change Assigned Driver for Delivery'), __('messages.Your delivery driver has been changed to') .' '. userNameById($order->delivery_driver_id));
            }
            

            // Return success response
            return $this->apiResponse('success', 200, __('messages.Driver') .' '. __('messages.SUCCESS.ASSIGN_DONE'), route('admin.order.list', ['type' => 'cancelled']));
    
        } catch (\Exception $e) {
            return $this->apiResponse('error', 500, $e->getMessage());
        }
    }
    
    /**
     * Function to send the assignment email
     */
    private function sendAssignmentEmail($order, $template, $type) {
        $stringToReplace = ['{{$name}}', '{{$old_status}}', '{{$new_status}}', '{{$order_id}}', '{{$amount}}', '{{$services}}', '{{$created_at}}', '{{$driver_name}}', '{{$driver_email}}', '{{$driver_phone}}'];
        if ($type == 'pickup') {
            $stringReplaceWith = [
                userNameById($order->user_id),
                $order->status,
                'pickup',
                $order->order_id,
                $order->total_amount,
                implode(',', $order->service_name),
                convertDate($order->created_at),
                userNameById($order->pickup_driver_id),
                $order->pickupDriver->email,
                $order->pickupDriver->driverDetail->phone_number,
            ];
        } else {
            $stringReplaceWith = [
                userNameById($order->user_id),
                $order->status,
                'deliver',
                $order->order_id,
                $order->total_amount,
                implode(',', $order->service_name),
                convertDate($order->created_at),
                userNameById($order->delivery_driver_id),
                $order->deliveryDriver->email,
                $order->deliveryDriver->driverDetail->phone_number,
            ];
        }
    
        $newval = str_replace($stringToReplace, $stringReplaceWith, $template->template);
        $newsubject = str_replace($stringToReplace, $stringReplaceWith, $template->subject);
        $emailData = $this->mailData($order->user->email, $newsubject, $newval, 'assign_driver', $template->id);
        $this->mailSend($emailData);
    }
    
    /**End method assignDriver**/

    /**
     * functionName : removeService
     * createdDate  : 19-09-2024
     * purpose      : remove the service
    */
    public function removeService($id){
        try{

            $order_service = OrderService::find($id);
            $count = OrderService::where('order_id',$order_service->order_id)->count();
            if( $count == 1){
                return $this->apiResponse('success',200, __('messages.Atlest one service is required for the order.'),['count' => $count]);
            }

            OrderService::where('id',$id)->delete();
            
            return $this->apiResponse('success',200, __('messages.Order service') .' '. __('messages.SUCCESS.DELETE_DONE'),['count' => $count]);
           
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method removeService**/


    /**
     * functionName : updateServiceAmount
     * createdDate  : 19-09-2024
     * purpose      : remove the service
    */
    public function updateServiceAmount($id, $amount, $qty)
    {
        try {
            $service = OrderService::findOrFail($id);
            $service->update(['amount' => $amount / $qty, 'qty' => $qty]);
    
            $orderId = $service->order_id;
    
            $total = OrderService::where('order_id', $orderId)
                ->selectRaw('SUM(amount * qty) as total')
                ->value('total');

           
            $orderTaxes = OrderTax::where('order_id', $orderId)->get();
    
            $orderTaxes->each(function ($tax) use ($total) {
                if ($tax->rate) {
                    $tax->update(['amount' => number_format((($total * $tax->rate) / 100),2)]);
                }
            });
    
            
            $deliveryCostConfig = ConfigSetting::where('type', 'delivery-cost')
                ->pluck('value', 'key');
    
            if ($total > ($deliveryCostConfig['FREE_DELIVERY']) ?? 0) {
                OrderTax::where('title', 'Delivery Fee')
                    ->where('order_id', $orderId)
                    ->update(['amount' => 0, 'rate' => 0]);
            }
            $order = Order::find($orderId);
            $total = $total + $order->taxes()->where('title','!=','Total Discount')->sum('amount') - $order->taxes()->where('title','Total Discount')->sum('amount');

            Order::where('id', $orderId)
                ->update(['total_amount' => $total]);
    
            return $this->apiResponse('success', 200, __('messages.Order service') .' '. __('messages..SUCCESS.UPDATE_DONE'));
    
        } catch (\Exception $e) {
            return $this->apiResponse('error', 500, $e->getMessage());
        }
    }
    
    /**End method updateServiceAmount**/

}
