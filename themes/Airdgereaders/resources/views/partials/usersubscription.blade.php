@php
    $subscription = account()->activeSubscription();
    $subscriptions = account()->subscriptions();

@endphp
<!-- user subscription component -->
    <div class="col-md-12 mt-10"></div>

    <div class="col-md-12">
        
        <div class="ereaders-shop-detail">
            <div class="flex justify-center relative -mt-24" id="alert-holder">
               
                    @if($subscription)
                    <div class="alert bg-gray-100">
                        <img class="mx-auto h-10" src="{{ theme_asset('images/status-active.png') }}">
                        <p>Active</p>
                    </div>
                    @else
                
                    <div class="alert bg-gray-100">
                        <img class="mx-auto h-10" src="{{ theme_asset('images/status-inactive.png') }}">
                        <p>Inactive</p>
                    </div>
                    <div class="alert border-gray-100">
                        <img class="mx-auto h-10" src="{{ theme_asset('images/ranc.jpg') }}">
                        <p>NGN {{ fiat_equivalent(account()->subscriptionWalletBalance(), 'NGN') }}</p>
                    </div>
                    @endif
              
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 ms-md-auto">
                    <div class="ereaders-detail-thumb-text">
                        <h2 class="uppercase text-bold">Active Subscription</h2>

                        <ul class="ereaders-detail-option" style="margin-top: 10px">
                        </ul>
                       
                        <div class="clearfix"></div>
                        <div class="overflow-x-scroll">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Activation Date</th>
                                    <th>Expiry Date</th>
                                    <th>Credit Balance</th>
                                </thead>
                                <tbody>
                                    @if($subscription)
                                    <tr>
                                        <td>{{ $subscription->subscription_id }}</td>
                                        <td>{{ $subscription->name }}</td>
                                        <td>{{ $subscription->updated_at }}</td>
                                        <td>{{ $subscription->next_payment_date }}</td>
                                        <td>{{ account()->subscriptionWalletBalance() }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                    <div class="clearfix"></div>
                    @if($subscription)
                    <div class=" float-right">
                        <a href="{{ route('subscriptions.refresh') }}" class="ereaders-detail-btn cursor-pointer">Refresh</a>

                        <a href="{{ route('subscriptions.cancel', ['subscription' => $subscription->subscription_id ] ) }}" class="ereaders-detail-btn bg-warning cursor-pointer">Cancel</a>

                         <a  data-toggle="modal" data-target="#buycreditModal"  class="ereaders-detail-btn cursor-pointer">Buy Credits</a>

                        <ul class="ereaders-detail-social">
                           
                        </ul>
                    </div>
                    @endif
                </div>
            </div>


            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 ms-md-auto">
                    <div class="ereaders-detail-thumb-text">
                        <h2 class="uppercase text-bold">Previous Subscriptions</h2>

                        <ul class="ereaders-detail-option" style="margin-top: 10px">
                        </ul>
                       
                        <div class="clearfix"></div>
                       
                        <div class="overflow-x-scroll">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Activation Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    @if($subscriptions)
                                        @foreach($subscriptions as $subscription)
                                        <tr>
                                            <td>{{ $subscription->subscription_id }}</td>
                                            <td>{{ $subscription->name }}</td>
                                            <td>{{ $subscription->updated_at }}</td>
                                            <td>{{ $subscription->next_payment_date }}</td>
                                            <td>{{ $subscription->paystack_status }}</td>
                                            <td>
                                                <a href="{{ route('subscriptions.cancel', ['subscription' => $subscription->subscription_id ] ) }}" class="text-xs border cursor-pointer">Disable</a>

                                                <a href="{{ route('subscriptions.enable', ['subscription' => $subscription->subscription_id ] ) }}" class="text-xs border cursor-pointer">Enable</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- settings Modal: modalPoll -->
    <div class="modal fade right" id="buycreditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
        @include('partials.buycredits_modal')
    </div>
    <!-- Modal: modalPoll -->
                

