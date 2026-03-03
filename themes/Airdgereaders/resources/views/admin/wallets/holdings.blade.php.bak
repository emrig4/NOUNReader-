@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Subscription</h2>
        </div>

       
 <!--  +"id": 342666
  +"domain": "test"
  +"status": "cancelled"
  +"subscription_code": "SUB_p68fflh4vxmw4xj"
  +"email_token": "cywbbm0rjxqq4qu"
  +"amount": 200000
  +"cron_expression": "0 * * * *"
  +"next_payment_date": null
  +"open_invoice": null
  +"createdAt": "2021-12-29T15:52:26.000Z"
  +"cancelledAt": "2021-12-31T11:00:05.000Z"
  +"integration": 149366
  +"plan": {#1851 ▶}
  +"authorization": {#1861 ▶}
  +"customer": {#1854 ▶}
  +"invoices": {#1852 ▶}
  +"invoices_history": {#2179 ▶}
  +"invoice_limit": 0
  +"split_code": null
  +"most_recent_invoice": {#2395 ▶}
  +"payments_count": 44 -->


  <!-- 
      +"id": 4103864
      +"domain": "test"
      +"invoice_code": "INV_lx2t7z3lhskloax"
      +"amount": 200000
      +"period_start": "2021-12-31T10:00:00.000Z"
      +"period_end": "2021-12-31T10:59:59.000Z"
      +"status": "success"
      +"paid": true
      +"paid_at": "2021-12-31T10:00:07.000Z"
      +"description": null
      +"createdAt": "2021-12-31T10:00:04.000Z"
      +"authorization": {#2181 ▶}
      +"subscription": {#2182}
      +"customer": {#2183 ▶}
      +"transaction": {#2184}

   -->
        <!-- Card Column -->
        
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
                        {{$subscription->status}}
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
                            <td>{{ $customer->first_name }} </td>
                            <td>{{ $customer->last_name }} </td>
                            <td>{{ $customer->email }} </td>
                            <td>{{ $customer->phone }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="body md:w-1/3 flex items-start justify-center">
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Payments:</div>
                    <div>{{$subscription->payments_count}}</div>
                </div>
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Created:</div>
                    <div>{{ date('d-m-Y', strtotime($subscription->createdAt)) }}</div>
                </div>
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Cancelled:</div>
                    <div>{{ date('d-m-Y', strtotime($subscription->cancelledAt)) }}</div>
                </div><div class="flex  text-sm w-full justify-between">
                    <div class="font-bold">Next Payment:</div>
                    <div>{{ $subscription->next_payment_date ? date('d-m-Y', strtotime($subscription->next_payment_date)) : '' }}</div>
                </div>
            </div>
            <div class="actions">
                <div class="dropdown -ml-3 lg:ml-auto">
                    <button class="btn-icon text-gray-600 hover:text-primary" data-toggle="dropdown-menu" aria-expanded="false"><span class="la la-ellipsis-v text-4xl leading-none"></span></button>
                    <div class="dropdown-menu text-left">
                        <a class="p-2 text-center border-gray-100 border rounded" href="#">Renew</a>
                        <a  class="p-2 text-center border-gray-100 border rounded" href="#">Disable</a>
                        <a  class="p-2 text-center border-gray-100 border rounded" href="#">Suspend</a>
                        <a  class="p-2 text-center border-gray-100 border rounded" href="#">Send Invoice</a>
                    </div>
                </div>
                <!-- <a href="#" class="btn h-4 btn_outlined btn_secondary mt-auto ml-auto lg:ml-0">Disable</a> -->
                <!-- <a href="#" class="btn h-4 btn_outlined btn_secondary mt-auto ml-auto lg:ml-0">Suspend</a> -->

            </div>
        </div>
    </div>


    <div class="card mt-5 p-5 overflow-x-scroll">
        <h3>Invoices</h3>
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Domain</th>
                    <th class="text-left uppercase">Code</th>
                    <th class="text-left uppercase">Amount</th>
                    <th class="text-left uppercase">Period Start</th>
                    <th class="text-left uppercase">Period End</th>
                    <th class="text-left uppercase">Status</th>
                    <th class="text-left uppercase">Paid</th>
                    <th class="text-left uppercase">Paid At</th>
                    <th class="text-left uppercase">Created At</th>
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
        var table = $('.yajra-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.subscriptions.show', $subscription->id ) }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'domain', name: 'domain'},
                {data: 'invoice_code', name: 'invoice_code'},
                {data: 'amount', name: 'amount'},
                {data: 'period_start', name: 'period_start'},
                {data: 'period_end', name: 'period_end'},
                {data: 'status', name: 'status'},
                {data: 'paid', name: 'paid'},
                {data: 'paid_at', name: 'paid_at'},
                {data: 'createdAt', name: 'createdAt'},
            ]
        });

        $('#DataTables_Table_0_filter input').addClass('form-control mb-4')
        $('#DataTables_Table_0_length').addClass('my-4')
        $('#DataTables_Table_0_length select').addClass('form-control w-40')
        console.log( $('#DataTables_Table_0 table  tbody tr') )

    });
</script>
@endpush
