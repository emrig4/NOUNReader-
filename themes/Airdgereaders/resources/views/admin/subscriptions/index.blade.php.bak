@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Subscriptions</h2>
        </div>

      
        
    <div class="card mt-5 p-5">
        <!-- <h3>SUBSCRIPTIONS</h3> -->
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Name</th>
                    <th class="text-left uppercase">Email</th>
                    <th class="text-left uppercase">Amount</th>
                    <th class="text-left uppercase">Start Date</th>
                    <th class="text-left uppercase">Payments</th>
                    <th class="text-left uppercase">Status</th>
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
        var table = $('.yajra-dt').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.subscriptions.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'plan.name', name: 'name'},
                {data: 'customer.email', name: 'email'},
                {data: 'amount', name: 'amount'},
                {data: 'start', name: 'start'},
                {data: 'payments_count', name: 'payments_count'},
                {data: 'status', name: 'status'},
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });

        $('#DataTables_Table_0_filter input').addClass('form-control mb-4')
        $('#DataTables_Table_0_length').addClass('my-4')
        $('#DataTables_Table_0_length select').addClass('form-control w-40')
        console.log( $('#DataTables_Table_0 table  tbody tr') )

    });
</script>
@endpush
