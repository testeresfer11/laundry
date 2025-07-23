<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VariantController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all the variants
    */
    public function getList(Request $request){
        try{
            $variants = Variant::when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('name','like',"%$request->search_keyword%")
                            ->orWhere('gender','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);
            return view("admin.variant.list",compact("variants"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 24-07-2024
     * purpose      : add the variant
    */
    public function add(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'name'            => 'required|string|unique:variants,name',
                'gender'          => 'required|in:Men,Women',
                'image'           => 'required|image|max:2048',
            ],[
                'name.unique' => 'The variant name already exists. Please choose another one.',
            ]);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors());
            }
            $ImgName = '';
            if ($request->hasFile('image')) {
                $ImgName = uploadFile($request->file('image'),'images/');
            }

            $existName = Variant::where('name',$request->name)->first();

            if($existName){
                return $this->apiResponse('error', 422, [
                    'name' => ['The variant name already exists. Please choose another one.']
                ]);
            }

            $variant = Variant::create([
                'name'      => $request->name,
                'gender'    => $request->gender ? $request->gender : null,
                'image'     => $ImgName,
            ]);
            $variant->image = asset('storage/images/'.$variant->image);

            return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.ADD_DONE'),$variant);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : edit
     * createdDate  : 24-07-2024
     * purpose      : edit the variant
    */
    public function edit(Request $request,$id = null){
        try{
            if($request->isMethod('get')){
                $variant = Variant::find($id);
                return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.FETCH_DONE'),$variant);
            }else{
                $validator = Validator::make($request->all(), [
                    'edit_variant_id' => 'required|exists:variants,id',
                    'name'            => 'required|string|max:255',
                    'gender'          => 'required|in:Men,Women'
                ]);
                
                if ($validator->fails()) {
                    return $this->apiResponse('error',422,$validator->errors()->first());
                }
    
                $ImgName = Variant::find($request->edit_variant_id)->image;
                if ($request->hasFile('image')) {
                    $ImgName = uploadFile($request->file('image'),'images/');
                }

                Variant::where('id',$request->edit_variant_id)->update([
                    'name'      => $request->name,
                    'gender'    => $request->gender ? $request->gender : null,
                    'image'     => $ImgName
                ]);
                
                $variant = Variant::find($request->edit_variant_id);
                $variant->image = asset('storage/images/'.$variant->image);

                return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.UPDATE_DONE'),$variant);
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
            Variant::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Variant status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : delete
     * createdDate  : 23-07-2024
     * purpose      : Delete the user by id
    */
    public function delete(Request $request, $id)
    {
        try {
            if ($id === "clear") {
                DB::table('variants')->delete();
            } else {
                $deleted = DB::table('variants')->where('id', $id)->delete();
    
                if (!$deleted) {
                    return response()->json([
                        'api_response' => 'error',
                        'message' => 'Variant not found.'
                    ], 404);
                }
            }
    
            return response()->json([
                'api_response' => 'success',
                'message' => 'Variant deleted successfully.'
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
