@extends('layouts.account')
@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('themes/airdgereaders/css/stats.css') }}" rel="stylesheet">
@endpush


@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        
        <!-- dashboard nav -->
        @include('partials.usermenu')

        <div class="container" style="width: 100%">
            <div class="row">
                @include('partials.userwallet', ['wallet' => $wallet, 'walletHistory' => $walletHistory])
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
        var table = $('#wallet-history').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('account.mywallethistory') }}",
            columns: [
                {data: 'reference', name: 'reference'},
                {data: 'remark', name: 'remark'},
                {data: 'type', name: 'type'},
                {data: 'status', name: 'status'},
                {data: 'ranc', name: 'ranc'},
                {data: 'amount', name: 'amount'},
                {data: 'currency', name: 'currency'},
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
        $('#wallet-history_length').addClass('hidden d-none')
        // console.log( $('#DataTables_Table_0 table  tbody tr') )

    });
</script>
@endpush

