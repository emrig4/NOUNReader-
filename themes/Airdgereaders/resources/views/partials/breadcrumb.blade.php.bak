<!--// SubHeader \\-->
<div class="ereaders-subheader" >
    <div class="ereaders-breadcrumb ereaders-book-breadcrumb" >
        <div class="container" >
            <div class="row" >
                <div class="col-md-12">
                    @unless ($breadcrumbs->isEmpty())
                         <ul>
                            @foreach ($breadcrumbs as $breadcrumb)

                                @if (!is_null($breadcrumb->url) && !$loop->last)
                                    <li ><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
                                @else
                                    <li class="active">{{ $breadcrumb->title }}</li>
                                @endif

                            @endforeach
                        </ul>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
<!--// SubHeader \\-->
