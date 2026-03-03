<?php

namespace App\Modules\Subscription\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Digikraaft\Paystack\Subscription;
use Digikraaft\Paystack\Plan;
use Digikraaft\Paystack\Paystack;
use App\Modules\Subscription\Models\PaystackSubscription;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;



class SubscriptionController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
        Paystack::setApiKey(config('paystacksubscription.secret', env('PAYSTACK_SECRET')));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        
        // dd(Subscription::list(['email' => 'airondev@gmail.com'])->data);
        // $plan = Plan::fetch('PLN_eohnjyhr4zvlyyd')->data;
        // dd($plan);

        if ($request->ajax()) {
            $subscriptions = Subscription::list()->data;
            $data = collect($subscriptions);
            // $data =  PaystackSubscription::latest()->get();
            return Datatables::of($data)
                // ->addIndexColumn()
                ->setRowId('id')
                ->addColumn('action', function($row){
                    
                    $showUrl = route('admin.subscriptions.show', $row->id);
                    $editUrl = route('admin.subscriptions.edit', $row->id);

                    $btn = "<a href='$showUrl' class='text-xs h-4 btn btn_primary'>View</a>";
                    // $btn = $btn."<a href='javascript:void(0)' class='text-xs h-4 btn btn_warning'>Edit</a>";
         
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);

            // return new JsonResponse(Datatables::of($data)->make(true));

        }else{
            // $subscriptions = PaystackSubscription::latest()->get();
            $subscriptions = Subscription::list()->data;
            return view('admin.subscriptions.index', compact('subscriptions'));
        }


    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('subscription::create');
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
    public function show(Request $request, $id)
    {   
        $subscription = Subscription::fetch($id)->data;
        $invoices = $subscription->invoices;
        $customer = $subscription->customer;
        
        $invoices_history = collect($subscription->invoices_history)->map(function($inv){
            $inv->createdAt = date('d-m-Y', strtotime($inv->createdAt));
            $inv->paid_at = date('d-m-Y', strtotime($inv->paid_at));
            $inv->period_start = date('d-m-Y', strtotime($inv->period_start));
            $inv->period_end = date('d-m-Y', strtotime($inv->period_end));
            return $inv;
        });

        // dd($invoices_history);

        if ($request->ajax()) {
            return Datatables::of($invoices_history)
                ->addIndexColumn()
                // ->setRowId('id')
                // ->addColumn('action', function($row){
                    
                //     $showUrl = route('admin.subscriptions.show', $row->id);
                //     $editUrl = route('admin.subscriptions.edit', $row->id);

                //     $btn = "<a href='$showUrl' class='text-xs h-4 btn btn_primary'>View</a>";
                //     // $btn = $btn."<a href='javascript:void(0)' class='text-xs h-4 btn btn_warning'>Edit</a>";
         
                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);

            // return new JsonResponse(Datatables::of($invoices_history)->make(true));
        }else{

            // dd(collect($invoices_history));
            return view('admin.subscriptions.show', compact('subscription', 'invoices', 'invoices_history', 'customer'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('subscription::edit');
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
}




/*INVOICE*/
    // +"id": 1537329198
    // +"domain": "test"
    // +"status": "success"
    // +"reference": "a71e41b7-b50a-5eeb-bd0d-c9c54a7f825b"
    // +"amount": 200000
    // +"message": null
    // +"gateway_response": "Approved"
    // +"paid_at": "2021-12-31T10:00:07.000Z"
    // +"created_at": "2021-12-31T10:00:06.000Z"
    // +"channel": "card"
    // +"currency": "NGN"
    // +"ip_address": null
    // +"metadata": {#2173 ▶}
    // +"log": null
    // +"fees": 3000
    // +"fees_split": null
    // +"authorization": {#2174 ▶}
    // +"customer": {#2175 ▶}
    // +"plan": {#2176}
    // +"subaccount": {#2177}
    // +"split": {#2178}
    // +"order_id": null
    // +"paidAt": "2021-12-31T10:00:07.000Z"
    // +"createdAt": "2021-12-31T10:00:06.000Z"
    // +"requested_amount": 200000
    // +"pos_transaction_data": null
    // +"source": null
    // +"fees_breakdown": null


    // INVOICE HISTORY
    // +"id": 4103864
    // +"domain": "test"
    // +"invoice_code": "INV_lx2t7z3lhskloax"
    // +"amount": 200000
    // +"period_start": "2021-12-31T10:00:00.000Z"
    // +"period_end": "2021-12-31T10:59:59.000Z"
    // +"status": "success"
    // +"paid": true
    // +"paid_at": "2021-12-31T10:00:07.000Z"
    // +"description": null
    // +"createdAt": "2021-12-31T10:00:04.000Z"
    // +"authorization": {#2181 ▶}
    // +"subscription": {#2182}
    // +"customer": {#2183 ▶}
    // +"transaction": {#2184}