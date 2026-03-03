@extends('layouts.account')
@push('css')
    <link href="{{ asset('themes/airdgereaders/css/stats.css') }}" rel="stylesheet">
@endpush

@php
@endphp



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        
        <!-- dashboard nav -->
        @include('partials.usermenu')

        <div class="container" style="width: 100%">
            <div class="row">
                 <div class="col-md-12">
        
        <div class="ereaders-shop-detail">
            <div class="flex justify-center relative -mt-24" id="alert-holder">
                <div class="alert text-center bg-white my-5  border border-gray-300">
                    <img class="mx-auto h-10" src="{{ theme_asset('images/ranc.jpg') }}">
                    <p class="text-bold">{{auth()->user()->notifications->count() }} Notifications</p>
                    <div class="mt-3 clearfix"></div>
                </div>  
            </div>
            <div class="row">
                <div class="col-md-12 ms-md-auto" style="overflow-x: scroll;">
                    <div class="ereaders-detail-thumb-text">
                        <h2 class="uppercase text-bold">Notifications</h2>

                        <div class="clearfix"></div>
                        <table id="wallet-history" class="table table-responsive">
                            <thead>
                                <th class="text-left uppercase">S/N</th>
                                <th class="text-left uppercase">Subject</th>
                                <th class="text-left uppercase">Message</th>
                                <th class="text-left uppercase">Date</th>
                                <th class="text-left uppercase">Action</th>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification )
                                    <tr class="{{ $notification->read_at ? '' : 'bg-success' }}">
                                        <td>{{ $notification->iteration  }}</td>
                                        <td>{{ $notification->data[0]['subject']  }}</td>
                                        <td>{{ $notification->data[0]['body']  }}</td>
                                        <td>{{ $notification->created_at->diffForHumans()  }}</td>
                                        <td><a class="{{ $notification->read_at ? 'btn btn-secondary' : 'btn btn-primary' }}" href="{{ route( 'account.notifications.read', $notification->id ) }}">
                                           {{ $notification->read_at ? 'Mark as Unread' : 'Mark as Read'   }}
                                        </a></td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table> 
                    </div>
                    
                </div>
            </div>

            <div class="flex justify-center flex-1 sm:hidden">
                {!! $notifications->render() !!}
            </div>

        </div>
    </div>
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')
@endpush

