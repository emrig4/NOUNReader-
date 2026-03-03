@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">Resource</h2>
             <div class="flex items-center ml-auto mt-5 lg:mt-0">
                <div class="w-1/3  mt-0">
                    <div class=" ml-2">
                        <a class="ml-5 btn  btn_secondary uppercase" href="{{ route('resources.show', $resource->slug) }}">
                            View
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
    <div class="lg:flex mt-5">
        <div class="card card_row">
            <div>
                <div class="image">
                    <i class="fileicon {{ $mainFile->icon() }}"></i>
                </div>
            </div>
            <div class="header md:w-1/3">
                <h5 class="my-2"></h5>
                <p>{{ $mainFile->filename }}</p>
            </div>
            <div class="body md:w-1/3 flex items-start justify-center">
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Views:</div>
                    <div>{{ $resource->read_count ?? 0 }}</div>
                </div>
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Published:</div>
                    <div>{{ $resource->is_published ? 'Yes' : "No" }}</div>
                </div>
                <div class="flex  border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Created:</div>
                    <div>{{ $resource->created_at->diffForHumans() }}</div>
                </div>
                <div class="flex border-b mb-4 text-sm w-full justify-between">
                    <div class="font-bold">Downloads:</div>
                    <div>{{ $resource->download_count }}</div>
                </div>
                <div class="flex  text-sm w-full justify-between">
                    <div class="font-bold">Author</div>
                    <div>{{ $resource->author()->fullname }}</div>
                </div>
            </div>
            <div class="actions">
                <div class="dropdown -ml-3 lg:ml-auto">
                    <button class="btn-icon text-gray-600 hover:text-primary" data-toggle="dropdown-menu" aria-expanded="false"><span class="la la-ellipsis-v text-4xl leading-none"></span></button>
                    <div class="dropdown-menu text-left">
                        <form method="POST" action="{{ route('admin.resources.delete', $resource->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-center cursor-pointer w-full border-gray-100 border rounded" href="">Delete</button>
                        </form>
                        <form method="GET" action="{{ route('admin.resources.unpublish', $resource->id) }}">
                            @csrf
                            @method('GET')
                            <button type="submit" class="p-2 text-center cursor-pointer w-full border-gray-100 border rounded" href="">{{ $resource->is_published ? 'Disable' : 'Enable' }}</button>
                        </form>
                        <a  class="p-2 text-center border-gray-100 border rounded" href="{{ route('resources.show', $resource->slug) }}">View</a>
                        <a  class="p-2 text-center border-gray-100 border rounded" href="{{ route('admin.resources.edit', $resource->slug) }}">Edit</a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="card mt-5 p-5 overflow-x-scroll">
        <h3>Reports</h3>
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Name</th>
                    <th class="text-left uppercase">Comment</th>
                    <th class="text-left uppercase">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resource->reports as $report)
                <tr>
                   <td class="text-left uppercase">{{ $report->id }}</td>
                   <td class="text-left uppercase">{{ $report->name }}</td>
                   <td class="text-left uppercase">{{ $report->comment }}</td>
                   <td class="text-left uppercase">{{ $report->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>



    <div class="card mt-5 p-5 overflow-x-scroll">
        <h3>Reviews</h3>
        <table class="table  yajra-dt  table_bordered mt-3 w-full">
            <thead>
                <tr class="text-sm">
                    <th class="text-left uppercase">ID</th>
                    <th class="text-left uppercase">Name</th>
                    <th class="text-left uppercase">Comment</th>
                    <th class="text-left uppercase">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resource->reviews as $review)
                <tr>
                   <td class="text-left uppercase">{{ $review->id }}</td>
                   <td class="text-left uppercase">{{ $review->name }}</td>
                   <td class="text-left uppercase">{{ $review->comment }}</td>
                   <td class="text-left uppercase">{{ $review->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </section>

  
  @endSection
  

@push('js')


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

@endpush
