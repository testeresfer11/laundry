<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PointManagementController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 21-11-2024
     * purpose      : Get the list for all the points
    */
    public function getList(Request $request){
        try{
            $points = Point::when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('offer_name','like',"%$request->search_keyword%")
                                ->orWhere('offer_type','like',"%$request->search_keyword%")
                                ->orWhere('points','like',"%$request->search_keyword%")
                                ->orWhere('max_order_amount','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);

            return view("admin.points.list",compact("points"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 21-11-2024
     * purpose      : add the point offer
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.points.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'offer_name'        => 'required|max:100',
                    'offer_type'        => 'required|in:discount,free_service',
                    'points'            => 'required|max:99999',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                Point::create([
                    'offer_name'        => $request->offer_name,
                    'description'       => $request->description ? $request->description : null,
                    'points'            => $request->points,
                    'offer_type'        => $request->offer_type,
                    'start_date'        => $request->start_date ? $request->start_date : null,
                    'end_date'          => $request->end_date ? $request->end_date : null,
                    'max_order_amount'  => $request->max_order_amount ? $request->max_order_amount  : null,
                ]);

                return redirect()->route('admin.points.list')->with('success','Point offer '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : changeStatus
     * createdDate  : 21-11-2024
     * purpose      : Update the Point status
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
            Point::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Point offer status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : delete
     * createdDate  : 21-11-2024
     * purpose      : Delete the points by id
    */
    public function delete($id){
        try{

            Point::where('id',$id)->delete();

            return response()->json(["status" => "success","message" => "Point offer ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/

    /**
     * functionName : view
     * createdDate  : 21-11-2024
     * purpose      : Get the detail of specific points
    */
    public function view($id){
        try{
            $point = Point::findOrFail($id);
            return view("admin.points.view",compact("point"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/

    /**
     * functionName : edit
     * createdDate  : 21-11-2024
     * purpose      : edit the point detail
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $point = Point::find($id);
                return view("admin.points.edit",compact('point'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'offer_name'        => 'required|max:100',
                    'offer_type'        => 'required|in:discount,free_service',
                    'points'            => 'required|max:99999',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                Point::updateOrCreate(['id' => $id],[
                    'offer_name'        => $request->offer_name,
                    'description'       => $request->description ? $request->description : null,
                    'points'            => $request->points,
                    'offer_type'        => $request->offer_type,
                    'start_date'        => $request->start_date ? $request->start_date : null,
                    'end_date'          => $request->end_date ? $request->end_date : null,
                    'max_order_amount'  => $request->max_order_amount ? $request->max_order_amount  : null,
                ]);
                return redirect()->route('admin.points.list')->with('success','Point offer '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/
}
