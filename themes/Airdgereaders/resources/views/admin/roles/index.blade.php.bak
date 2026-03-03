@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Roles</h2>
            <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                        <button class="ml-5 btn  btn_secondary uppercase" data-toggle="modal"
                            data-target="#exampleModalCenteredCreateUser">
                            Create
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
                    <th class="text-left uppercase">Name</th>
                    <th class="text-left uppercase">Permissions</th>
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
            <form method="POST"  action="{{ route('admin.roles.store') }}" class="modal-content" style="min-width: 600px;">
                <div class="modal-header">
                    <h2 class="modal-title">Create Role</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>

                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input value="{{ old('name') }}" 
                            type="text" 
                            class="form-control" 
                            name="name" 
                            placeholder="Name" required>
                    </div>
                
                    <label for="permissions" class="form-label">Assign Permissions</label>
                    <table class="table table-striped">
                        <thead>
                            <th scope="col" width="1%">
                            <input type="checkbox" name="all_permission"></th>
                            <th scope="col" width="20%">Name</th>
                            <th scope="col" width="1%">Guard</th> 
                        </thead>

                        @foreach($permissions as $permission)
                            <tr>
                                <td>
                                    <input type="checkbox" 
                                    name="permission[{{ $permission->name }}]"
                                    value="{{ $permission->name }}"
                                    class='permission'>
                                </td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                    </table>

                    <button type="submit" class="btn btn_primary h-8 mt-10">Create</button>
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
            ajax: "{{ route('admin.roles.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'permissions', name: 'permissions.name'},
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

<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked',true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked',false);
                });
            }
            
        });
    });
</script>
@endpush

