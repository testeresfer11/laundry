<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{HelpDesk,QueryResponse};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class HelpDeskController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all the help desk
    */
    public function getList($type){
        try{
            $tickets = new HelpDesk;
            $data = $tickets->clone()->when(($type == 'open'),function($query){
                        $query->where('status','!=','Completed');
                    })->when(($type == 'close'),function($query){
                        $query->where('status','Completed');
                    })->orderBy("id","desc")->paginate(10);
            $openCount = $tickets->clone()->where('status','!=','Completed')->count();
            $closeCount = $tickets->clone()->where('status','Completed')->count();

            $recentTime = Carbon::now()->subMinutes(5);
            
            $newlyUpdatedTicketIds = HelpDesk::whereIn('ticket_id', $data->pluck('id'))
                ->where('created_at', '>=', $recentTime)
                ->pluck('ticket_id')
                ->unique()
                ->toArray();

            return view("admin.helpDesk.list",compact("data","openCount","closeCount",'type','newlyUpdatedTicketIds'));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 23-07-2024
     * purpose      : add the query
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.helpDesk.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'user_name'         => 'required|string|max:255',
                    'email'             => 'required|email:rfc,dns',
                    'description'       => 'required',
                ],[
                    'description.required' => 'Query is required',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
               
                HelpDesk::Create([
                    'user_id'           => authId(),
                    'user_name'         => $request->user_name,
                    'email'             => $request->email,
                    'phone_number'      => $request->phone_number ? $request->phone_number : '',
                    'query'             => $request->description,
                ]);

                return redirect()->route('admin.helpDesk.list',['type' => 'open'])->with('success','Query '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End Method Add */

    /**
     * functionName : response
     * createdDate  : 23-07-2024
     * purpose      : Add the response
    */
    public function response(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $response = HelpDesk::find($id);
                return view("admin.helpDesk.response",compact('response'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'response' => 'nullable|required_without:response_image|string',
                    'response_image' => 'nullable|required_without:response|file|mimes:jpeg,jpg,png,gif|max:10240'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                HelpDesk::where('id' , $id)->update(['status'=> 'In Progress']);

                $ImgName = '';
                if ($request->hasFile('response_image')) {
                    $ImgName = uploadFile($request->file('response_image'),'images/');
                }
                QueryResponse::create([
                    'help_id'=>$id,
                    'user_id' => authId(),
                    'response' => $request->response ?? null,
                    'response_image' => $ImgName,
                ]);

                $helpdesk = HelpDesk::find($id);
                
                $this->sendPushNotification($helpdesk->user_id, __('messages.New Message from Admin'), __('messages.You have a new response to your helpdesk ticket:') .' '. $helpdesk->title);

                return redirect()->route('admin.helpDesk.response',['id'=>$id])->with('success','Reply '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method response**/


    /**
     * functionName : changeStatus
     * createdDate  : 23-07-2024
     * purpose      : Update the ticket status done mark as complete
    */
    public function changeStatus(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'id'        => 'required',
            ]);
            if ($validator->fails()) {
                if($request->ajax()){
                    return response()->json(["status" =>"error", "message" => $validator->errors()->first()],422);
                }
            }
           
            HelpDesk::where('id',$request->id)->update(['status' => 'Completed']);

            return response()->json(["status" => "success","message" => __('messages.Ticket status') .' '. __('messages.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/
}
