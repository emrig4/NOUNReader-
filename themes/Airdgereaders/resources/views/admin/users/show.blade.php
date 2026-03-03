@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">User - {{ $user->first_name }}</h2>
        </div>

       
        
    <div class="lg:flex mt-5">
        <div class="card card_row">
            <div>
                <div class="image">
                    <img src="{{ theme_asset( 'admin/assets/images/avatar.png') }}">
                    <label class="custom-checkbox absolute top-0 left-0 mt-2 ml-2">
                        <input type="checkbox" data-toggle="cardSelection">
                        <span></span>
                    </label>
                    <div class="badge badge_outlined badge_secondary uppercase absolute top-0 right-0 mt-2 mr-2">
                        {{$user->status}}
                    </div>
                </div>
            </div>
            <div class="header md:w-1/3">
                <h5 class="my-2"></h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->first_name }} </td>
                            <td>{{ $user->last_name }} </td>
                            <td>{{ $user->email }} </td>
                            <td>{{ $user->phone }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="body md:w-1/3 flex items-start justify-center">
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Created:</div>
                    <div>{{ $user->created_at->format('d-m-Y') }}</div>
                </div>
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Wallet Balance:</div>
                    <div>NGN {{ fiat_equivalent($user->CreditWallet->ranc, 'NGN') }}</div>
                   
                </div>
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Subscription Balance:</div>
                    <div>NGN {{ fiat_equivalent($user->SubscriptionWallet->ranc, 'NGN') }}</div>
                </div>
            </div>
            <div class="actions">
                <div class="dropdown -ml-3 lg:ml-auto">
                    <button class="btn-icon text-gray-600 hover:text-primary" data-toggle="dropdown-menu" aria-expanded="false"><span class="la la-ellipsis-v text-4xl leading-none"></span></button>
                    <div class="dropdown-menu text-left">
                        <a class="p-2 text-center border-gray-100 border rounded"  data-toggle="modal" data-target="#exampleModalCenteredCreatePlan" href="#">Credit Wallet</a>
                        <a class="p-2 text-center border-gray-100 border rounded" href="#">Suspend</a>
                        <a class="p-2 text-center border-gray-100 border rounded" href="#">Send Invoice</a>
                    </div>
                </div>
                <!-- <a href="#" class="btn h-4 btn_outlined btn_secondary mt-auto ml-auto lg:ml-0">Disable</a> -->
                <!-- <a href="#" class="btn h-4 btn_outlined btn_secondary mt-auto ml-auto lg:ml-0">Suspend</a> -->

            </div>
        </div>
    </div>


    <div class="card mt-5 p-5 overflow-x-scroll">
        <h3>Transactions</h3>
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Reference</th>
                    <th class="text-left uppercase">Remark</th>
                    <th class="text-left uppercase">Amount</th>
                    <th class="text-left uppercase">Currency</th>
                    <th class="text-left uppercase">Type</th>
                    <th class="text-left uppercase">Status</th>
                    <th class="text-left uppercase">Ranc</th>
                    <th class="text-left uppercase">Created At</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    </section>


    <!-- CREDIT WALLET MODAL -->
    <div class="modal" id="exampleModalCenteredCreatePlan" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_centered max-w-2xl">
            
            <form method="POST" action="{{ route('admin.wallets.credit') }}" class="modal-content" style="min-width: 400px;"> 
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">CREDIT WALLET</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Amount</label>
                        <input type="number" class="form-control" name="amount" placeholder="Enter text here">
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
                    <input type="text" hidden class="form-control" value="{{$user->email}}" name="email" placeholder="Enter text here">

                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="submit" class="btn btn_primary ml-2 uppercase">CREDIT</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

  
  @endSection
  

@push('js')
 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
        var table = $('.yajra-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.wallets.show', $user->CreditWallet->id ) }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'reference', name: 'reference'},
                {data: 'remark', name: 'remark'},
                {data: 'amount', name: 'amount'},
                {data: 'currency', name: 'currency'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'ranc', name: 'ranc'},
                {data: 'created_at', name: 'createdAt'},
            ]
        });

        $('#DataTables_Table_0_filter input').addClass('form-control mb-4')
        $('#DataTables_Table_0_length').addClass('my-4')
        $('#DataTables_Table_0_length select').addClass('form-control w-40')
        console.log( $('#DataTables_Table_0 table  tbody tr') )

       

    });
</script>
@endpush
