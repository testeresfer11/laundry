<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentPageController extends Controller
{
    /**
     * functionName : contentPage
     * createdDate  : 23-07-2024
     * purpose      : get and update the content page detail
    */
    public function contentPageDetail(Request $request , $slug){
        try{
            if($request->isMethod('get')){
               $content_detail =  ContentPage::where('slug',$slug)->first();
                return view("admin.contentPage.update",compact('content_detail'));
            }elseif( $request->isMethod('post') ){
                $rules = [
                    'title'         => 'required|string|max:255',
                    'content'       => 'required',
                ];
                
                $validator = Validator::make($request->all(), $rules);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
               
               ContentPage::where('slug',$slug)->update([
                    'title'     => $request->title,
                    'content'     => $request->content,
               ]);

                return redirect()->back()->with('success',ucfirst(str_replace('-', ' ', $slug)).' '.config('constants.SUCCESS.UPDATE_DONE'));
            }
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method add**/
}
