<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    use SendResponseTrait;

    /**
     * functionName : getList
     * createdDate  : 31-07-2024
     * purpose      : Get the list for all the variants
    */
    public function getList(Request $request){
        try{
            $vehicles = Vehicle::when($request->filled('search_keyword'),function($query) use($request){
                    $query->where('name','like',"%$request->search_keyword%");
                })
                ->when($request->filled('status'),function($query) use($request){
                    $query->where('status',$request->status);
                })->orderBy("id","desc")->paginate(10);
            return view("admin.vehicle.list",compact("vehicles"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 24-07-2024
     * purpose      : add the vehicle
    */
    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name'            => 'required|string|max:255',
            ]);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $vehicle = Vehicle::create([
                'name'  => $request->name
            ]);

            return $this->apiResponse('success',200,'Vehicle '.config('constants.SUCCESS.ADD_DONE'),$vehicle);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : add
     * createdDate  : 24-07-2024
     * purpose      : edit the vehicle
    */
    public function edit(Request $request,$id = null){
        try{
            if($request->isMethod('get')){
                $vehicle = Vehicle::find($id);
                return $this->apiResponse('success',200,'Vehicle '.config('constants.SUCCESS.FETCH_DONE'),$vehicle);
            }else{
                $validator = Validator::make($request->all(), [
                    'edit_vehicle_id' => 'required|exists:vehicles,id',
                    'name'            => 'required|string|max:255',
                ]);
                
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
    
                Vehicle::where('id',$request->edit_vehicle_id)->update([
                    'name'  => $request->name
                ]);
                
                $vehicle = Vehicle::find($request->edit_vehicle_id);

                return $this->apiResponse('success',200,'Vehicle '.config('constants.SUCCESS.UPDATE_DONE'),$vehicle);
            }
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : changeStatus
     * createdDate  : 24-07-2024
     * purpose      : Update the vehicle status
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
            Vehicle::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Vehicle status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
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
    public function delete(Request $request, $id)
    {
        try {
            if ($id === "clear") {
                DB::table('vehicles')->delete();
            } else {
                $deleted = DB::table('vehicles')->where('id', $id)->delete();
    
                if (!$deleted) {
                    return response()->json([
                        'api_response' => 'error',
                        'message' => 'Vehicle not found.'
                    ], 404);
                }
            }
    
            return response()->json([
                'api_response' => 'success',
                'message' => 'Vehicle deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'api_response' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**End method delete**/
}
