@php
@endphp
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <button><span class="fa fa-return"></span></button>
            <h2 class="text-xl leading-tight">Pricing</h2>
        </div>

       

       
        <div class="card card_column p-4 h-full">
            <div class="flex justify-between">
                <p class="text-lg text-tertiary leading-tight">{{ $pricing->title }}</p>
                <div class="text-right">
                    <p class="rounded-lg p-1 px-2 ">{{ $pricing->currency() }}</small> {{ $pricing->price() }}</p>
                </div>
            </div>
            <form method="POST" class="" action="{{ route('admin.pricings.update', $pricing->id) }}">
            @csrf 
            @method('PATCH') 
                <div class="border-t space-y-8"> 
                    <div class="my-2">
                        <label class="label block mb-2" for="input">Title</label>
                        <input type="text" value="{{$pricing->title}}" name="title" class="form-control">
                    </div>

                    <div class="my-2">
                        <label class="label block mb-2" for="input">Features</label>
                        <input type="text" value="{{$pricing->features}}" class="form-control" name="features" placeholder="Enter text here">
                        <small class="block mt-2">Comma separated list of features</small>
                    </div>

                    <div class="my-2">
                        <label class="label block mb-2" for="input">Description</label>
                        <input type="text" value="{{$pricing->description}}" class="form-control" name="description" placeholder="Enter text here">
                    </div>



                    <div class="my-2">
                        <label class="label block mb-2" for="input">Amount</label>
                        <input type="text" value="{{$pricing->amount}}"  class="form-control" name="amount" placeholder="Enter text here">
                        <small class="block mt-2">Amount is in Ranc</small>
                    </div>

                    <div class="my-2">
                        <label class="label block mb-2" for="input">Plan Code</label>
                        <input type="text" value="{{$pricing->plan_id}}"  class="form-control" name="plan_id" placeholder="Enter text here">
                        <small class="block mt-2">Paystack plan code here</small>
                    </div>


                    <div class="my-2">
                        <label class="label block mb-2" for="input">Notes</label>
                        <input type="text" value="{{$pricing->note}}" class="form-control" name="note" placeholder="Enter text here">
                    </div>
                </div>
           
                <div class="border-t mt-8 w-full flex justify-end">
                    <button href="#" type="submit" class="btn btn_primary mt-2"><span class="la la-save">Save</span></button>
                </div>
            </form>
        </div>
        

    </section>

  
  @endSection
  @push('js')
  @endPush


