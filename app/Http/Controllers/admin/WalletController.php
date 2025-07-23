<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * functionName : getList
     * createdDate  : 21-11-2024
     * purpose      : Get the list for all wallet details
    */
  
    public function getList(Request $request)
    {
        try {
            // Default date range for 'month' filter
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);

            $wallets = Wallet::query()
                ->when($request->filled('search_keyword'), function ($query) use ($request) {
                    $searchKeyword = "%" . $request->search_keyword . "%";
                    $query->where(function ($query) use ($searchKeyword) {
                        $query->where('wallet_id', 'like', $searchKeyword)
                            ->orWhereHas('user', function ($q) use ($searchKeyword) {
                                $q->where('first_name', 'like', $searchKeyword)
                                ->orWhere('last_name', 'like', $searchKeyword)
                                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$searchKeyword]);
                            });
                    });
                })
                ->when($request->filled('amount'), function ($query) use ($request) {
                    $query->where('amount', $request->amount); // assuming exact match (e.g., "50.00")
                })
                ->when($request->filled('record') && $request->record === 'month', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate->toDateString(), $endDate->toDateString()]);
                });

            $wallets = $wallets->orderBy('id', 'desc')->paginate(10);

            return view("admin.wallet.list", compact("wallets"));
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method getList**/

    /**
     * functionName : view
     * createdDate  : 21-11-2024
     * purpose      : Get the wallet detail
    */
    public function view($id){
        try{
            $wallet = Wallet::findOrFail($id);
            return view("admin.wallet.view",compact("wallet"));
        }catch(\Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    /**End method view**/
}
