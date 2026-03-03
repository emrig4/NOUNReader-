@php
@endphp
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Plan</h2>
        </div>


        <div class="card card_column p-4 h-full">
            <div class="flex mb-10 border-b justify-between">
                <p class="text-lg text-tertiary leading-tight">{{ $plan->name }}</p>
                <div class="text-right">
                    <p class="rounded-lg p-1 px-2 ">{{ $plan->currency }}</small> {{ $plan->amount }}</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('admin.plans.update', $plan->id) }}" > 
                @method('PATCH')
                @csrf
                <div class=" space-y-8"> 
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Name</label>
                        <input type="text" value="{{ $plan->name }}" name="name" class="form-control" placeholder="Enter text here">
                        <small class="block mt-2">Please make it short.</small>
                    </div>
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Description</label>
                        <input type="text" value="{{ $plan->description }}" class="form-control" name="description" placeholder="Enter text here">
                        <small class="block mt-2"></small>
                    </div>
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Amount</label>
                        <input type="text" value="{{ $plan->amount }}" class="form-control" name="amount" placeholder="Enter text here">
                        <small class="block mt-2"></small>
                    </div>
                     <div class="w-full">
                        <label class="label block mb-2" for="input">Currency</label>
                        
                        <div class="custom-select">
                            <select name="currency" class="form-control">
                                <option selected value="{{ $plan->currency }}">{{ $plan->currency }}</option>
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
                                <option value="{{ $plan->interval }}">{{ $plan->interval }}</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="annually">Annually</option>
                            </select>
                            <div class="select-icon la la-caret-down"></div>
                        </div>
                    </div>
                </div>
                <div class="border-t mt-8 w-full flex justify-end">
                    <button href="#" type="submit" class="btn btn_primary mt-2"><span>Save</span></button>
                </div>
            </form>
        </div>

    </section>


  @endSection
  @push('js')
  @endPush


