@php
    $subscription = account()->activeSubscription();
    $subscriptions = account()->subscriptions();

@endphp
<!-- user subscription component -->
    <div class="col-md-12 mt-10"></div>

    <div class="col-md-12">
        
        <div class="ereaders-shop-detail">
            <div class="flex justify-center relative -mt-24" id="alert-holder">
                <div class="alert text-center bg-white my-5  border border-gray-300">
                    <img class="mx-auto h-10" src="{{ theme_asset('images/ranc.jpg') }}">
                    <p class="text-bold">{{$wallet->ranc}} RNC</p>

                    <div class="mt-3 clearfix"></div>
                    <!-- if($subscription) -->
                    <div class=" float-right">
                       <a  data-toggle="modal" data-target="#buycreditModal"  class="ereaders-detail-btn cursor-pointer">Withdraw<i class="icon ereaders-shopping-bag"></i></a>
                    </div>
                    <!-- endif -->

                </div>  
            </div>
            <div class="row">
                <div class="col-md-12 ms-md-auto" style="overflow-x: scroll;">
                    <div class="ereaders-detail-thumb-text">
                        <h2 class="uppercase text-bold">Transaction History</h2>


                        <div class="clearfix"></div>
                        <table id="wallet-history" class="table table-responsive">
                            <thead>
                                <th class="text-left uppercase">Reference</th>
                                <th class="text-left uppercase">Remark</th>
                                <th class="text-left uppercase">Type</th>
                                <th class="text-left uppercase">Status</th>
                                <th class="text-left uppercase">Ranc</th>
                                <th class="text-left uppercase">Amount</th>
                                <th class="text-left uppercase">Currency</th>
                                <th class="text-left uppercase">Action</th>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                       
                    </div>
                    
                </div>
            </div>

        </div>
    </div>


    <!-- settings Modal: modalPoll -->
    <div class="modal fade right" id="buycreditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
        @include('partials.withdrawcredits_modal')
    </div>
    <!-- Modal: modalPoll -->
                

