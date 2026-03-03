<?php

namespace App\Modules\Wallet\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Wallet\Models\CreditWalletTransaction;
use App\Modules\Wallet\Models\CreditWalletHolding;

use App\Modules\Wallet\Http\Traits\WalletTrait;
use App\Models\User;
class CreditWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('wallet::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('wallet::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function withdraw(Request $request)
    {
        $user = User::whereEmail($request->email)->first() ?? auth()->user();
        
        $walletBal = WalletTrait::creditWalletBalance();
        if($walletBal < $request->ranc_amount) {
            notify()->error('Insufficient Balance');
            return redirect()->back(); 
        }

        //
        $req =  WalletTrait::debitWallet($request->ranc_amount);
        
        CreditWalletHolding::create([
            'ranc' => $req->ranc,
            'reference' => $req->reference,
            'status' => $req->status,
            'description' => 'Withdrawal request',
            'credit_wallet_id' => $req->credit_wallet_id,
            'user_id' => $user->id,
        ]);

        notify()->success('withdrawal request created');
        return redirect()->back();
    }


    /**
     * cancel withdrawal.
     * @param Request $request
     * @return Renderable
     */
    public function cancelWithdrawal(CreditWalletTransaction $transaction)
    {
        
        if($transaction->status != 'pending'){
            notify()->error('can no longer cancelle request ');
            return redirect()->back();
        }

        $transaction->update(['status' => 'cancelled']);
        $transaction->walletHolding()->update(['status' => 'cancelled']);
        WalletTrait::refundCreditWallet($transaction->ranc);
        
        notify()->success('withdrawal request cancelled ' . $transaction->ranc_amount . 'refunded');
        return redirect()->back();
    }

 



}
