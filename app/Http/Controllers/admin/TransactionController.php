<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class TransactionController extends Controller
{
    /**
     * functionName : getList
     * createdDate  : 23-07-2024
     * purpose      : Get the list for all transactions
    */
    public function getList(){
        try{
            $transactions = Payment::orderBy("id","desc")->paginate(10);
            return view("admin.transaction.list",compact("transactions"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : view
     * createdDate  : 23-07-2024
     * purpose      : Get the detail of the specific transaction
    */
    public function view($id){
        try{
            $transaction = Payment::findOrFail($id);
            return view("admin.transaction.view",compact("transaction"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/
}
