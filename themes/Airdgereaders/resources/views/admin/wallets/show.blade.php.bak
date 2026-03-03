@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Wallet Address:  - {{ $wallet->reference }}</h2>
        </div>

     
    <div class="lg:flex mt-5">
        <div class="card card_row">
            <div>
                <div class="image">
                    <img src="{{ $wallet->user->profile_photo_url }}">
                    <label class="custom-checkbox absolute top-0 left-0 mt-2 ml-2">
                        <input type="checkbox" data-toggle="cardSelection">
                        <span></span>
                    </label>
                    <div class="badge badge_outlined badge_secondary uppercase absolute top-0 right-0 mt-2 mr-2">
                    </div>
                </div>
            </div>
            <div class="header md:w-1/3">
                <h5 class="my-2"></h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $wallet->user->name }} </td>
                            <td>{{ $wallet->user->email }} </td>
                            <td>{{ $wallet->ranc }} RNC </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="body md:w-1/3 flex items-start justify-center">
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Account Number:</div>
                    <div>{{ $wallet->user->account->bank_account_number }}</div>
                </div>
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Bank Name:</div>
                    <div>{{ $wallet->user->account->bank_name }}</div>
                </div>
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Acount Name:</div>
                    <div>{{ $wallet->user->account->bank_account_name }}</div>
                </div>
            </div>
            <div class="actions">
                <div class="dropdown -ml-3 lg:ml-auto">
                    <button class="btn-icon text-gray-600 hover:text-primary" data-toggle="dropdown-menu" aria-expanded="false"><span class="la la-ellipsis-v text-4xl leading-none"></span></button>
                    <div class="dropdown-menu text-left">
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="card mt-5 p-5 overflow-x-scroll">
        <table id="wallet-withdrawals" class="table table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">Reference</th>
                    <th class="text-left uppercase">Remark</th>
                    <th class="text-left uppercase">Date</th>
                    <th class="text-left uppercase">Type</th>
                    <th class="text-left uppercase">Status</th>
                    <th class="text-left uppercase">Ranc</th>
                    <th class="text-left uppercase">Amount</th>
                    <th class="text-left uppercase">Currency</th>
                    <th class="text-left uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>



    </section>

  
  @endSection
  

@push('js')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
        var table = $('#wallet-withdrawals').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.wallets.show', $wallet->id ) }}",
            columns: [
                {data: 'reference', name: 'reference'},
                {data: 'remark', name: 'remark'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'ranc', name: 'ranc'},
                {data: 'amount', name: 'amount'},
                {data: 'currency', name: 'currency'},
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        $('#wallet-withdrawals_filter input').addClass('form-control mb-4')
        $('#wallet-withdrawals_length').addClass('my-4')
        $('#wallet-withdrawals_length select').addClass('form-control w-40')

    });
</script>
@endpush
