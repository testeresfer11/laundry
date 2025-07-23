<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ConfigSetting;
use App\Models\TimeShedule;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeSheduleController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all the variants
    */
    public function getList(Request $request){
        try{
            $data = TimeShedule::orderBy("id","asc")->get();
            return view("admin.config-setting.time-shedule",compact("data"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 24-07-2024
     * purpose      : edit the variant
    */
    public function edit(Request $request,$id = null){
        try{
            if($request->isMethod('get')){
                $variant = TimeShedule::find($id);
                return $this->apiResponse('success',200,'Time shedule '.config('constants.SUCCESS.FETCH_DONE'),$variant);
            }else{
                $validator = Validator::make($request->all(), [
                    'edit_shedule_id' => 'required|exists:time_shedules,id',
                    'start_time'      => 'required',
                    'end_time'        => 'required',
                ]);
                
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
    
                TimeShedule::where('id',$request->edit_shedule_id)->update([
                    'start_time'    => $request->start_time,
                    'end_time'      => $request->end_time
                ]);
                
                $shedule = TimeShedule::find($request->edit_shedule_id);

                return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.UPDATE_DONE'),$shedule);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : changeStatus
     * createdDate  : 24-07-2024
     * purpose      : Update the variant status
    */
    public function changeStatus(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
                'id'        => 'required',
                "status"    => "required|in:0,1",
            ]);
            if ($validator->fails()) {
                if($request->ajax()){
                    return response()->json(["status" =>"error", "message" => $validator->errors()->first()],422);
                }
            }
            TimeShedule::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : timeSlot
     * createdDate  : 24-07-2024
     * purpose      : Update the variant status
    */
    public function timeSlot(Request $request){
        try{
            
            $validator = Validator::make($request->all(), [
               "time_slot" => 'required|min:1|max:100'
            ]);
            if ($validator->fails()) {
                if($request->ajax()){
                    return response()->json(["status" =>"error", "message" => $validator->errors()->first()],422);
                }
            }
            ConfigSetting::where('type', 'time-slot')->where('key' ,'TIME_SLOT')->update(['value' => $request->time_slot]);

            return response()->json(["status" => "success","message" => "Time slot ".config('constants.SUCCESS.UPDATE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/
}
