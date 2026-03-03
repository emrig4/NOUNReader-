@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Role | {{ $role->name }}</h2>
            <!-- <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                        <button class="ml-5 btn  btn_secondary uppercase" data-toggle="modal"
                            data-target="#exampleModalCenteredCreateUser">
                            Assign
                        </button>
                    </div>
                </div>
            </div> -->
        </div>

      
        
    <div class="card mt-5 p-5">
        <div class="bg-light p-4 rounded">
        <h1>{{ ucfirst($role->name) }} Role</h1>
        <div class="lead">
            
        </div>
        
        <div class="container mt-4">

            <h2>Assigned permissions</h2>


            <!-- <table  class="table table-striped">
                <thead>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="1%">Guard</th> 
                </thead>

                <div >
                    @foreach($rolePermissions as $permission)
                        <tr >
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                        </tr>
                    @endforeach
                </div>
            </table>
 -->
            <table class="table  yajra-dt  table_bordered mt-3 w-full">
                <thead>
                    <tr class="text-sm">
                        <th class="text-left uppercase">ID</th>
                        <th class="text-left uppercase">Name</th>
                        <th class="w-40 text-left uppercase">Guard</th>
                    </tr>
                </thead>
                <tbody>
                  
                </tbody>
            </table>


        </div>

    </div>
    <div class="mt-10">
        <a href="#" data-toggle="modal" data-target="#exampleModalCenteredCreateUser" class="btn btn_primary  h-8">Edit</a>
        <a href="{{ route('admin.roles.index') }}" class="btn btn_secondary h-8">Back</a>
    </div>
    </div>


    </section>

     <!-- Centered -->
    <div class="modal" id="exampleModalCenteredCreateUser" data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
        <div class="modal-dialog modal-dialog_centered max-w-2xl">
            
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="modal-content" style="min-width: 600px;">
                @method('patch')
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title">Edit Role</h2>
                    <button type="button" class="btn-icon close la la-times" data-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input value="{{ $role->name }}" 
                        type="text" 
                        class="form-control" 
                        name="name" 
                        placeholder="Name" required>
                    </div>
                    
                    <div style="height: 300px; overflow-y: scroll;">
                        <label for="permissions" class="form-label">Assign Permissions</label>
                        <table class="table table-striped">
                            <thead>
                                <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                                <th scope="col" width="20%">Name</th>
                                <th scope="col" width="1%">Guard</th> 
                            </thead>

                            @foreach($permissions as $permission)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                        name="permission[{{ $permission->name }}]"
                                        value="{{ $permission->name }}"
                                        class='permission'
                                        {{ in_array($permission->name, $rolePermissions->pluck('name')->toArray() ) 
                                            ? 'checked'
                                            : '' }}>
                                    </td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <button type="submit" class="btn btn btn_primary h-8 mt-10">Save changes</button>
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
            ajax: "{{ route('admin.permissions.index') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {
                    data: 'guard_name',
                    name: 'guard_name',
                    orderable: true,
                    searchable: true
                },
            ]
        });

        $('#DataTables_Table_0_filter input').addClass('form-control mb-4')
        $('#DataTables_Table_0_length').addClass('my-4')
        $('#DataTables_Table_0_length select').addClass('form-control w-40')
        console.log( $('#DataTables_Table_0 table  tbody tr') )


        $('.yajra-dt').on('click', '.btn-delete[data-remote]', function (e) { 
            e.preventDefault();
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });

            // var url = $(this).data('remote');
            // $.ajax({
            //     url: url,
            //     type: 'DELETE',
            //     dataType: 'json',
            //     data: {method: '_DELETE', submit: true}
            // }).always(function (data) {
            //     $('.yajra-dt').DataTable().draw(false);
            // });

            alert('action not allowed');
        });

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

