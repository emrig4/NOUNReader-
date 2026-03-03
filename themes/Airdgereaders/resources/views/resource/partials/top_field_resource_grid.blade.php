<div class="ereaders-blog ereaders-blog-grid">
    <ul class="row">
        @foreach($resources as $resource)
            <li class="col-md-4">
                @include('resource.partials.inc.grid_card', $resource)
            </li>
        @endforeach
    </ul>
</div>

