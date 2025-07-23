<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : getList
     * createdDate  : 14-08-2024
     * purpose      : Get the list for all the promotion
    */
    public function getList(Request $request){
        try{
            $promotions = Promotion::when($request->filled('search_keyword'),function($query) use($request){
                        $query->where(function($query) use($request){
                            $query->where('title','like',"%$request->search_keyword%")
                                ->orWhere('discount','like',"%$request->search_keyword%");
                        });
                    })
                    ->when($request->filled('status'),function($query) use($request){
                        $query->where('status',$request->status);
                    })->orderBy("id","desc")->paginate(10);

            return view("admin.promotion.list",compact("promotions"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : add
     * createdDate  : 14-08-2024
     * purpose      : add the pomotion
    */
    public function add(Request $request){
        try{
            if($request->isMethod('get')){
                return view("admin.promotion.add");
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'discount'          => 'required|max:100',
                    'title'             => 'required|max:255',
                    'exp_date'          => 'required',
                    'min_order'         => 'required|max:100',
                    'max_discount'      => 'required|max:100',
                    'image'             => 'required|max:2048',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                $ImgName = '';
                if ($request->hasFile('image')) {
                    $ImgName = uploadFile($request->file('image'),'images/');
                }

                $description = str_replace('"', '', $request->description);
                
                Promotion::create([
                    'discount'      => $request->discount,
                    'title'         => $request->title,
                    'exp_date'      => $request->exp_date,
                    'min_order'     => $request->min_order,
                    'max_discount'  => $request->max_discount,
                    'image'         => $ImgName,
                    'description'   => $description ?: '',
                ]);


                return redirect()->route('admin.promotion.list')->with('success','Promotion '.config('constants.SUCCESS.ADD_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/

    /**
     * functionName : changeStatus
     * createdDate  : 14-08-2024
     * purpose      : Update the Promotion status
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
            Promotion::where('id',$request->id)->update(['status' => $request->status]);

            return response()->json(["status" => "success","message" => "Promotion status ".config('constants.SUCCESS.CHANGED_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method changeStatus**/

    /**
     * functionName : delete
     * createdDate  : 14-07-2024
     * purpose      : Delete the promotion by id
    */
    public function delete($id){
        try{
            $ImgName = Promotion::find($id)->image;

            if($ImgName != null){
                deleteFile($ImgName,'images/');
            }

            Promotion::where('id',$id)->delete();

            return response()->json(["status" => "success","message" => "Promotion ".config('constants.SUCCESS.DELETE_DONE')], 200);
        }catch(\Exception $e){
            return response()->json(["status" =>"error", $e->getMessage()],500);
        }
    }
    /**End method delete**/

    /**
     * functionName : view
     * createdDate  : 15-08-2024
     * purpose      : Get the detail of specific promotion
    */
    public function view($id){
        try{
            $promotion = Promotion::findOrFail($id);
            return view("admin.promotion.view",compact("promotion"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/

        /**
     * functionName : edit
     * createdDate  : 14-08-2024
     * purpose      : edit the promotion detail
    */
    public function edit(Request $request,$id){
        try{
            if($request->isMethod('get')){
                $promotion = Promotion::find($id);
                return view("admin.promotion.edit",compact('promotion'));
            }elseif( $request->isMethod('post') ){
                $validator = Validator::make($request->all(), [
                    'discount'          => 'required|max:100',
                    'title'             => 'required|max:255',
                    'exp_date'          => 'required',
                    'min_order'         => 'required|max:100',
                    'max_discount'      => 'required|max:100',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                $promotion = Promotion::find($id);
                $ImgName = $promotion->image ? $promotion->image : '';
                if ($request->hasFile('image')) {
                    deleteFile($ImgName,'images/');
                    $ImgName = uploadFile($request->file('image'),'images/');

                }

                Promotion::updateOrCreate(['id' => $id],[
                    'discount'      => $request->discount,
                    'title'         => $request->title,
                    'exp_date'      => $request->exp_date,
                    'min_order'     => $request->min_order,
                    'max_discount'  => $request->max_discount,
                    'image'         => $ImgName,
                    'description'   => $request->description ? $request->description : '',
                ]);
                return redirect()->route('admin.promotion.list')->with('success','Promotion '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method edit**/
}
