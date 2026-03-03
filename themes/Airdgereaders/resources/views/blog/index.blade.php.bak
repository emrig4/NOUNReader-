@extends('layouts.public', ['title' => 'Browse Fields'])
@push('meta')
@endpush
@push('css')
@endpush



@section('content')
    <div class="ereaders-main-content">

        <!--// Main Section \\-->
        <div class="ereaders-main-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        
                        <!-- grid -->
                        @include('blog.partials.blog_grid' , $posts)

                        <!--// Pagination \\-->
                        
                        <div class="ereaders-pagination">
                            {!! $posts->render() !!}
                        </div>

                        <!--// Pagination \\-->
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

    </div>


@endsection

