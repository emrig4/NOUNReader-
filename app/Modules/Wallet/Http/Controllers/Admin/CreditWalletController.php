<?php

namespace App\Modules\Wallet\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Wallet\Models\CreditWalletTransaction;
use App\Modules\Wallet\Models\CreditWallet;
use App\Modules\Wallet\Models\CreditWalletHolding;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\Wallet\Http\Traits\WalletTrait;
use App\Models\User;

class CreditWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function walletTransaction(Request $request)
    {   
        $type = $request->query('type'); //earnings, withdrawals

        // dd($type);


        $transactions = CreditWalletTransaction::whereType($type)->get();
        
        $pendingCount =  CreditWalletTransaction::whereStatus('pending')->count();
        $processedCount =  CreditWalletTransaction::whereStatus('processed')->count();
        $rejectedCount =  CreditWalletTransaction::whereStatus('rejected')->count();
        $totalAmount =  CreditWalletTransaction::whereStatus('processed')->sum('ranc');

        if ($request->ajax()) {
            $data = collect($transactions);
            return Datatables::of($data)
                ->addColumn('action', function($row){    
                    $processUrl = route('admin.process-withdrawal', $row->id);
                    $cancelUrl = route('admin.reject-withdrawal', $row->id);
                    $showUrl = route('admin.wallets.show', $row->credit_wallet_id);

                    $viewBtn = "<a href='$showUrl' style='padding: 2px' class='text-xs h-4 btn bg-gray-600'>View Wallet</a>";


                    $btn = "<a href='$cancelUrl' style='padding: 2px' class='text-xs h-4 btn btn_danger'>Reject</a> <a href='$processUrl' style='padding: 2px' class='text-xs h-4 btn btn_primary'>Process</a> $viewBtn";

                   
                    if($row->type == 'withdrawal' && $row->status == 'pending'){
                        return $btn;
                    }else{
                        return $viewBtn;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }else{

            if($type == 'withdrawal'){
                return view('admin.wallets.withdrawals', compact('pendingCount', 'processedCount', 'rejectedCount', 'totalAmount'));
            }

            if($type == 'earning'){
                return view('admin.wallets.earnings', compact('totalAmount'));
            }
           
        }
        
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function withdraw(Request $request)
    {
        $user = User::whereEmail($request->email)->first() ?? auth()->user();
        
        $walletBal = WalletTrait::subscriptionWalletBalance();
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
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function credit(Request $request)
    {
        $user = User::whereEmail($request->email)->first() ?? auth()->user();
        $ranc_amount = $request->ranc_amount ?? ranc_equivalent( $request->amount, $request->currency);

        WalletTrait::creditSubscriptionWallet($ranc_amount, 'credit', $user->id );
        
        notify()->success('user wallet credited created');
        return redirect()->back();
    }


     /**
     * show wallet.
     * @param Request $request
     * @return Renderable
     */
    public function show(CreditWallet $wallet)
    {  

        $transactions = CreditWalletTransaction::where('credit_wallet_id', $wallet->id)->get();
        if (request()->ajax()) {
            $data = collect($transactions);
            return Datatables::of($data)
                ->addColumn('action', function($row){    
                    $processUrl = route('admin.process-withdrawal', $row->id);
                    $cancelUrl = route('admin.reject-withdrawal', $row->id);
                    $showUrl = route('admin.wallets.show', $row->credit_wallet_id);

                    $viewBtn = "<a href='$showUrl' style='padding: 2px' class='text-xs h-4 btn bg-gray-600'>View Wallet</a>";


                    $btn = "<a href='$cancelUrl' style='padding: 2px' class='text-xs h-4 btn btn_danger'>Reject</a> <a href='$processUrl' style='padding: 2px' class='text-xs h-4 btn btn_primary'>Process</a>";

                   
                    if($row->type == 'withdrawal' && $row->status == 'pending'){
                        return $btn;
                    }else{
                        return;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.wallets.show', compact('wallet'));
    }


    /**
     * cancel withdrawal.
     * @param Request $request
     * @return Renderable
     */
    public function rejectWithdrawal(CreditWalletTransaction $transaction)
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


    /**
     * cancel withdrawal.
     * @param Request $request
     * @return Renderable
     */
    public function processWithdrawal(CreditWalletTransaction $transaction)
    {
        
        if($transaction->status != 'pending'){
            notify()->error('Cannot process this request');
            return redirect()->back();
        }

        $transaction->update(['status' => 'processed']);
        $transaction->walletHolding()->update(['status' => 'completed']);
        // $transaction->payout()->create(['status' => 'completed']);
        
        notify()->success('withdrawal request processed ');
        return redirect()->back();
    }


 



}
