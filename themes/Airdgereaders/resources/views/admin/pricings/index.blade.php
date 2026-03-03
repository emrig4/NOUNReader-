@php
@endphp
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Pricing</h2>
            <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <form class="w-2/3 items-center mt-0" action="#">
                    <label class="form-control-addon-within  rounded-full overflow-hidden">
                        <input type="text" class="form-control border-none" placeholder="Search">
                        <span class="flex items-center pr-4"><button type="button" class="dark:text-gray-700 dark:hover:text-primary text-secondary hover:text-primary btn btn-link la la-search text-2xl leading-none"></button></span>
                    </label>
                </form>
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                    </div>
                </div>
            </div>
        </div>

       

        <!-- Card Column -->
        <div class="grid grid-cols-1  sm:grid-cols-3" >
            
            <div class="lg:px-4 mb-5">
                <a href="{{ route('admin.pricings.single', $basic->slug ) }}" class="card card_column p-4 cursor-pointer h-full">
                    <div class="flex justify-between">
                        <p class="text-xl font-bold text-tertiary leading-tight">{{ $basic->title }}</p>
                        <div class="text-right">
                            <p class="rounded-lg p-1 px-2 ">{{ $basic->currency() }}</small> {{ $basic->price() }}</p>
                        </div>
                    </div>
                    <div class="border-t mb-10">
                        <div class="mt-8">
                            @foreach($basic->features() as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </div>
                    </div>
                   
                    <div class="border-t mt-2">
                       <p><span class="font-bold">Plan Code:</span> {{ $basic->plan_id }}</p>
                    </div>
                </a>
            </div>

            <div class="lg:px-4 mb-5">
                <a href="{{ route('admin.pricings.single', $standard->slug ) }}" class="card card_column p-4 cursor-pointer h-full">
                    <div class="flex justify-between">
                        <p class="text-xl font-bold text-tertiary leading-tight">{{ $standard->title }}</p>
                        <div class="text-right">
                            <p class="rounded-lg p-1 px-2 ">{{ $standard->currency() }} </small>{{ $standard->price() }}</p>
                        </div>
                    </div>
                    <div class="border-t mb-10">
                        <div class="mt-8">
                            @foreach($standard->features() as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </div>
                    </div>
                   
                    <div class="border-t mt-2">
                       <p><span class="font-bold">Plan Code:</span> {{ $standard->plan_id }}</p>
                    </div>
                </a>
            </div>
        
            <div class="lg:px-4 mb-5">
                <a href="{{ route('admin.pricings.single', $pro->slug ) }}" class="card card_column p-4 cursor-pointer h-full">
                    <div class="flex justify-between">
                        <p class="text-xl font-bold text-tertiary leading-tight">{{ $pro->title }}</p>
                        <div class="text-right">
                            <p class="rounded-lg p-1 px-2 ">{{ $pro->currency() }}</small> {{ $pro->price() }}</p>
                        </div>
                    </div>
                    <div class="border-t mb-10">
                        <div class="mt-8">
                            @foreach($pro->features() as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </div>
                    </div>
                   
                    <div class="border-t mt-2">
                       <p><span class="font-bold">Plan Code:</span> {{ $pro->plan_id }}</p>
                    </div>
                </a>
            </div>
        </div> 

    </section>

      <!-- Basic -->
    <div class="modal" id="exampleModal" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog max-w-2xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Modal Title</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn_primary ml-2 uppercase">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Centered -->
    <div class="modal" id="exampleModalCenteredCreatePlan" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_centered max-w-2xl">
            
            <form method="POST" action="{{ route('admin.plans.store') }}" class="modal-content" style="min-width: 400px;"> 
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">Create Paystack Plan</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter text here">
                        <small class="block mt-2">Please make it short.</small>
                    </div>
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Description</label>
                        <input type="text" class="form-control" name="description" placeholder="Enter text here">
                        <small class="block mt-2"></small>
                    </div>
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Amount</label>
                        <input type="text" class="form-control" name="amount" placeholder="Enter text here">
                        <small class="block mt-2"></small>
                    </div>
                     <div class="w-full">
                        <label class="label block mb-2" for="input">Currency</label>
                        
                        <div class="custom-select">
                            <select name="currency" class="form-control">
                                <option value="USD">USD</option>
                                <option value="NGN">NGN</option>
                            </select>
                            <div class="select-icon la la-caret-down"></div>
                        </div>

                        <small class="block mt-2"></small>
                    </div>
                     <div class="w-full">
                        <label class="label block mb-2" for="input">Interval</label>
                        <div class="custom-select">
                            <select name="interval" class="form-control">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annually">Annually</option>
                            </select>
                            <div class="select-icon la la-caret-down"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="submit" class="btn btn_primary ml-2 uppercase">Create Plan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Scrollable -->
    <div class="modal" id="exampleModalScrollable" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_scrollable max-w-2xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Modal Title</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn_primary ml-2 uppercase">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Side -->
    <div class="modal modal_aside" id="exampleModalAside" data-animations="bounceInRight, bounceOutRight">
        <div class="modal-dialog max-w-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Modal Title</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn_secondary uppercase" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn_primary uppercase ml-2">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

  
  @endSection
  @push('js')
  @endPush


