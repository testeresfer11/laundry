<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\{Service,ServiceVariant};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all the user
    */
    public function getList(Request $request){
        try{
            $services = Service::when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('name','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);
            return view("admin.service.list",compact("services"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 23-07-2024
     * purpose      : add the user
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.service.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'name'                  => 'required|string|max:255',
                    'image'                 => 'required|image|max:2048',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $ImgName = '';
                if ($request->hasFile('image')) {
                    $ImgName = uploadFile($request->file('image'),'images/');
                }
                Service::create([
                    'name'            => $request->name,
                    'description'     => $request->description ? $request->description : '',
                    'image'           => $ImgName,
                ]);


                return redirect()->route('admin.service.list')->with('success','Service '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : add
     * createdDate  : 23-07-2024
     * purpose      : add the user
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $service = Service::find($id);
                return view("admin.service.edit",compact('service'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'name'                  => 'required|string|max:255',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $ImgName = Service::find($id)->image;
                if ($request->hasFile('image')) {
                    $ImgName = uploadFile($request->file('image'),'images/');
                }
                Service::where('id',$id)->update([
                    'name'            => $request->name,
                    'description'     => $request->description ? $request->description : '',
                    'image'           => $ImgName,
                ]);


                return redirect()->route('admin.service.list')->with('success','Service '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

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
            Service::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Service status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
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
    // public function delete($id){
    //     try{
    //         ServiceVariant::where('service_id',$id)->delete();
    //         Service::find($id)->delete();

    //         return response()->json(["status" => "success","message" => "Service ".config('constants.SUCCESS.DELETE_DONE')], 200);
    //     }catch(\Exception $e){
    //         return response()->json(["status" =>"error", $e->getMessage()],500);
    //     }
    // }
    public function delete(Request $request, $id)
    {
        try {
            if ($id === "clear") {
                DB::table('services')->delete();
            } else {
                $deleted = DB::table('services')->where('id', $id)->delete();
    
                if (!$deleted) {
                    return response()->json([
                        'api_response' => 'error',
                        'message' => 'Service not found.'
                    ], 404);
                }
            }
    
            return response()->json([
                'api_response' => 'success',
                'message' => 'Service deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'api_response' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**End method delete**/

    
    /**
     * functionName : removeVariant
     * createdDate  : 25-07-2024
     * purpose      : remove the service variant by id
    */
    public function removeVariant($id){
        try{
            $service = ServiceVariant::find($id);
            $service_id = $service->service_id;

            ServiceVariant::find($id)->delete();

            return $this->apiResponse('success',200,'Service variant '.config('constants.SUCCESS.REMOVE_DONE'),ServiceVariant::where('service_id',$service_id)->count());
        }catch(\Exception $e){
            return $this->apiResponse('error',500, $e->getMessage());
        }
    }
    /**End method removeVariant**/

    /**
     * functionName : variantList
     * createdDate  : 25-07-2024
     * purpose      : Get the list of variants for model
    */
    public function variantList($id){
        try{
            $html = '';
            foreach(getCommonList('variant',$id) as $key =>  $value){
                $html .= "<div class='p-2 rounded checkbox-form mb-3'>
                  <label class='checkbox' for=".$key.">
                    <input type='checkbox' class='variant_id'  value=".$key." id=".$key.">
                    <span class='checkmark'></span>
                    ".$value."
                  </label>
                  </div>";
            }
            return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.FETCH_DONE'),$html);
        }catch(\Exception $e){
            return $this->apiResponse('error',500, $e->getMessage());
        }
    }
    /**End method delete**/

    /**
     * functionName : addVariant
     * createdDate  : 25-07-2024
     * purpose      : Add the variants as per service
    */
    public function addVariant(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'service_id'   => 'required',
                'variant_ids'  => 'required',
            ]);
            
            if ($validator->fails()) {
                return $this->apiResponse('error',422,$validator->errors()->first());
            }

            $html = '';
            foreach(array_unique(json_decode($request->variant_ids)) as $value){
                $data = ServiceVariant::create([
                    'service_id'        => $request->service_id,
                    'variant_id'        => $value,
                ]);
                
                $html .="<tr data-id='".$data->id."'>
                                    <td>
                                        <input type='hidden' name='variant_id[]' value='".$data->variant_id."'>".($data->variant->name)."</td>
                                    <td>
                                        <div class='row'>
                                            <div class='col-6'>
                                                <input type='number' name='variant_price[]' value='".$data->price."' class='form-control'>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type='button' class='removeVariant' value='Remove'>
                                    </td>
                                </tr>";
            }
            return $this->apiResponse('success',200,'Variant '.config('constants.SUCCESS.ADD_DONE'),$html);
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /**End method addVariant**/

    /**
     * functionName : variant
     * createdDate  : 25-07-2024
     * purpose      : get and Save the price and variant as per service specifically
    */
    public function variant(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $service = Service::find($id);
                return view("admin.service.variant",compact('service'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'variant_id'            => 'array',
                    'variant_price'         => 'array',
                    'variant_price.*'       => 'max:1000'
                ],[
                    
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->with('error',$validator->errors()->first());
                    // return redirect()->back()->withErrors($validator)->withInput();
                }
                if($request->filled('variant_id')){
                    $count = count($request->variant_id);
                    for($i=0;$i<$count;$i++){
                        ServiceVariant::updateOrCreate([
                            'service_id'        => $id,
                            'variant_id'        => ($request->variant_id)[$i]
                        ],[
                            'price'             => ($request->variant_price)[$i]
                        ]);
                    }
                }
                return redirect()->route('admin.service.list')->with('success','Service variant '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method variant**/
}
