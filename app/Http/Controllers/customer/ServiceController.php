<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\{Service,ServiceVariant};
use App\Traits\SendResponseTrait;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use SendResponseTrait;
    /**
     * functionName : list
     * createdDate  : 27-08-2024
     * purpose      : get the list of service page
    */
    public function list(Request $request){
        try{
            
            $services = Service::where('status',1)->select('id','image','name')->get();
            

            $data = [];

            foreach($services as $service){
                $service_variants = ServiceVariant::where('service_variants.service_id', $service->id)
                                ->join('variants', 'service_variants.variant_id', '=', 'variants.id')
                                ->select('service_variants.id', 'service_variants.price', 'variants.name', 'variants.gender','variants.image')
                                ->get()
                                ->groupBy('gender');
                array_push($data,[
                    "id"        => $service->id,
                    "image"     => $service->image,
                    "name"      => $service->name,
                    'men'   => $service_variants->get('Men', collect()),  
                    'women' => $service_variants->get('Women', collect()) 
                ]);
            }

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Service List') .' '. $message,$data );
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method list */

   
    /**
     * functionName : variant
     * createdDate  : 27-08-2024
     * purpose      : get the variants of the service by id
    */
    public function variant(Request $request,$id){
        try{
            
            $service_variants = ServiceVariant::where('service_variants.service_id', $id)
                ->join('variants', 'service_variants.variant_id', '=', 'variants.id')
                ->select('service_variants.id', 'service_variants.price', 'variants.name', 'variants.gender')
                ->get()
                ->groupBy('gender');

            $data = [
                'men'   => $service_variants->get('Men', collect()),  
                'women' => $service_variants->get('Women', collect()) 
            ];

            $message = trans('messages.SUCCESS.FETCH_DONE');
            return $this->apiResponse('success',200, __('messages.Service Variant List') .' '. $message,$data );
        }catch(\Exception $e){
            return $this->apiResponse('error',500,$e->getMessage());
        }
    }
    /*end method variant */

}
