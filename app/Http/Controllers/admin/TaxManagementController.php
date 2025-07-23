<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaxManagementController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 10-09-2024
     * purpose      : Get the list of all taxes
    */
    public function getList(Request $request){
        try{
            $taxes = Tax::when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('label','like',"%$request->search_keyword%")
                                    ->orWhere('percentage','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);
            return view("admin.tax.list",compact("taxes"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 10-09-2024
     * purpose      : add the tax
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.tax.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'label'     => 'required|string|max:255',
                    'percentage'=> 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
               
                Tax::create([
                    'label'            => $request->label,
                    'percentage'       => $request->percentage,
                ]);


                return redirect()->route('admin.tax.list')->with('success','Tax '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : edit
     * createdDate  : 10-09-2024
     * purpose      : add the tax
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $tax = Tax::find($id);
                return view("admin.tax.edit",compact('tax'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'label'     => 'required|string|max:255',
                    'percentage'=> 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                Tax::where('id',$id)->update([
                   'label'            => $request->label,
                    'percentage'       => $request->percentage,
                ]);


                return redirect()->route('admin.tax.list')->with('success','Tax '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/

    /**
     * functionName : changeStatus
     * createdDate  : 10-09-2024
     * purpose      : Update the tax status
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
            Tax::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Tax status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : delete
     * createdDate  : 10-09-2024
     * purpose      : Delete the tax by id
    */
    public function delete($id){
        try{
            Tax::find($id)->delete();

            return response()->json(["status" => "success","message" => "Tax ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/
}
