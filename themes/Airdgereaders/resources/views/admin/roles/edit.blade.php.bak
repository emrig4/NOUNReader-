@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Edit Role | {{ $role->name }}</h2>
            <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                        <a href="{{ url()->previous() }}" class="ml-5 btn  btn_secondary uppercase" >
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

      
        
    <div class="card mt-5 p-5">
        <!-- <h3>SUBSCRIPTIONS</h3> -->
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

        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input value="{{ $role->name }}" 
                    type="text" 
                    class="form-control" 
                    name="name" 
                    placeholder="Name" required>
            </div>
            
            <label for="permissions" class="form-label">Assign Permissions</label>

            <table class="table table-striped">
                <thead>
                    <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="1%">Guard</th> 
                </thead>

                @foreach($permissions as $permission)
                    <tr style="max-height: 500px; overflow-y: scroll;">
                        <td>
                            <input type="checkbox" 
                            name="permission[{{ $permission->name }}]"
                            value="{{ $permission->name }}"
                            class='permission'
                            {{ in_array($permission->name, $rolePermissions) 
                                ? 'checked'
                                : '' }}>
                        </td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                @endforeach
            </table>

            <button type="submit" class="btn btn_primary">Save changes</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-default">Back</a>
        </form>

    </div>


    </section>



  @endSection
  

@push('js')



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

