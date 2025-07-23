<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HolidayManagement;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 31-07-2024
     * purpose      : Get the list for all the Holiday
    */
    public function getList(Request $request){
        try{
            $holidays = HolidayManagement::orderBy("id","desc")->paginate(10);
            return view("admin.config-setting.holiday",compact("holidays"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 31-07-2024
     * purpose      : add the Holiday
    */
    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'h_date'            => 'required|unique:holiday_management,h_date',
                'description'       => 'required'
            ]);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $holiday = HolidayManagement::create([
                'h_date'        => $request->h_date,
                'description'   => $request->description
            ]);

            return $this->apiResponse('success',200,'Holiday '.config('constants.SUCCESS.ADD_DONE'),$holiday);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : add
     * createdDate  : 31-07-2024
     * purpose      : edit the holiday
    */
    public function edit(Request $request,$id = null){
        try{
            if($request->isMethod('get')){
                $holiday = HolidayManagement::find($id);
                return $this->apiResponse('success',200,'Holiday '.config('constants.SUCCESS.FETCH_DONE'),$holiday);
            }else{
                $validator = Validator::make($request->all(), [
                    'edit_holiday_id'   => 'required|exists:holiday_management,id',
                    'description'       => 'required',
                    'h_date'            => 'required|unique:holiday_management,h_date,'.$request->edit_holiday_id,
                ]);
                
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
    
                HolidayManagement::where('id',$request->edit_holiday_id)->update([
                    'h_date'  => $request->h_date,
                    'description'   => $request->description
                ]);
                
                $holiday = HolidayManagement::find($request->edit_holiday_id);

                return $this->apiResponse('success',200,'Holiday '.config('constants.SUCCESS.UPDATE_DONE'),$holiday);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : changeStatus
     * createdDate  : 31-07-2024
     * purpose      : Update the holiday status
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
            HolidayManagement::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Holiday status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : delete
     * createdDate  : 31-07-2024
     * purpose      : Delete the user by id
    */
    public function delete($id){
        try{
            HolidayManagement::find($id)->delete();

            return response()->json(["status" => "success","message" => "Holiday ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/
}
