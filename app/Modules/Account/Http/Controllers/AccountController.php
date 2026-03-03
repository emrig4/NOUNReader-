<?php

namespace App\Modules\Account\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Wallet\Models\CreditWalletTransaction;
use App\Modules\Resource\Models\Resource;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Resource\Models\ResourceAuthor;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $account = auth()->user()->account ?? null;

if (!$account) {
    // Auto-create account if it doesn't exist
    $account = new \App\Modules\Account\Models\Account();
    $account->user_id = auth()->id();
    $account->save();
}

$resources = $account->resources()->paginate(20);
        return view('account::index', ['resources' => $resources]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('account::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('account::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('account::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }


    /**
     * My works.
     * @return Renderable
     */
    public function myWorks()
    {
        $resources = auth()->user()->account->resources;
        return view('account::myworks', ['resources' => $resources]);
    }


    /**
     * My works.
     * @return Renderable
     */
    public function myWallet()
    {
        $wallet = auth()->user()->CreditWallet;
        $walletHistory = CreditWalletTransaction::where('wallet_id', $wallet->id);
        return view('account::mywallet', compact('wallet','walletHistory'));
    }

    /**
     * myProfile.
     * @return Renderable
     */
    public function myProfile($username)
    {   
        
        $author = ResourceAuthor::where('username', $username)->firstOrFail();
        return view('account::profile', compact('author'));
    }

    /**
     * My works.
     * @return Renderable
     */
    public function creditWalletHistory()
    {
       if (request()->ajax()) {
            $wallet = auth()->user()->CreditWallet;
            $walletHistory = CreditWalletTransaction::where('credit_wallet_id', $wallet->id)->get();
            $data = collect($walletHistory);
            return Datatables::of($data)
                ->addColumn('action', function($row){    
                    $showUrl = route('admin.subscriptions.show', $row->id);
                    $cancelUrl = route('wallet.cancel-withdrawal', $row->id);

                    $btn = "<a href='$cancelUrl' style='padding: 2px' class='text-xs h-4 btn btn-danger'>Cancel</a>";
                    if($row->type == 'withdrawal' && $row->status == 'pending'){
                        return $btn;
                    }else{
                        return;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{
            $wallet = auth()->user()->CreditWallet;
            $walletHistory = CreditWalletTransaction::where('credit_wallet_id', $wallet->id);
            return view('account::mywallet', compact('wallet','walletHistory'));
        }
    }


    /**
     * My notifications.
     * @return Renderable
     */
    public function myNotifications()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        // dd($notifications);
        return view('account::notifications', compact('notifications'));
        
    }

    public function markNotificationAsRead($id)
    {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification->read_at) {
            $notification->markAsUnRead();
        }else{
            $notification->markAsRead();
        }

        return redirect()->back();
    }


}
