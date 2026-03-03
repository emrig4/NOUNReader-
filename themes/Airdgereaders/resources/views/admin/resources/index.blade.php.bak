@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Resources</h2>
            <div class="flex items-center space-x-0 ml-auto mt-5 lg:mt-0">
                <div class="w-1/2  mt-0">
                    <div class=" ml-2">
                        <button class="ml-5 btn h-8  btn_secondary uppercase" data-toggle="modal"
                            data-target="#exampleModalCenteredCreateUser">
                            Publish
                        </button>
                    </div>
                </div>

                <div class="w-1/2  mt-0">
                    <div class=" ml-2">
                        <button class="ml-5 btn h-8  btn_secondary uppercase" data-toggle="modal"
                            data-target="#downloadS3ListModal">
                            List
                        </button>
                    </div>
                </div>

            </div>
        </div>

      
        
    <div class="card mt-5 p-5">
        <!-- <h3>SUBSCRIPTIONS</h3> -->
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Title</th>
                    <th class="text-left uppercase">Type</th>
                    <th class="text-left uppercase">Field</th>
                    <th class="text-left uppercase">Price</th>
                    <th class="text-left uppercase">Currency</th>
                    <th class="text-left uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
              
            </tbody>
        </table>
    </div>


    </section>

    <!-- Centered -->
    <div class="modal" id="exampleModalCenteredCreateUser" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_centered max-w-2xl">
            
            <form method="POST"  action="{{ route('admin.resources.import') }}" enctype="multipart/form-data" class="modal-content" style="min-width: 400px;"> 
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">Publish Resources</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Select Sheet</label>
                        <input type="file"  name="sheet" class="form-control" placeholder="Select Sheet">
                        <small class="block mt-2"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="submit" class="btn btn_primary ml-2 uppercase">Publish</button>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <!-- DOwnload lisr -->
    <div class="modal" id="downloadS3ListModal" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_centered max-w-2xl">
            
            <form method="POST"  action="{{ route('admin.resources.lists3') }}" class="modal-content" style="min-width: 400px;"> 
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">List Download Bucket Files</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <label class="label block mb-2" for="input">Enter Directory</label>
                        <input type="text"  name="dir" class="form-control" placeholder="Enter Directory name or leave empty">
                        <small class="block mt-2"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="flex ml-auto">
                        <button type="submit" class="btn h-8 btn_primary ml-2 uppercase">Download</button>
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
            ajax: "{{ route('admin.resources.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'title', name: 'title'},
                {data: 'type', name: 'type'},
                {data: 'field', name: 'field'},
                {data: 'price', name: 'price'},
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
        console.log( $('#DataTables_Table_0 table  tbody tr') )

    });
</script>
@endpush

