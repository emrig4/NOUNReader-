<h2 class="ereaders-section-heading">Details</h2>

<div class="ereaders-book-detail">
    <ul>

        <!-- Title FIRST -->
        <li>
            <h6>Title</h6>
            <p>{{ $resource->title ?? 'Not available' }}</p>
        </li>

        <!-- Number of Pages SECOND -->
        <li>
            <h6>Number of Pages</h6>
            <p>
                @if($resource->pages)
                    <strong>{{ $resource->pages }} pages</strong>
                @else
                    <span class="text-muted">Pages not available</span>
                @endif
            </p>
        </li>

        <!-- Author(s) THIRD -->
        <li>
            <h6>Author(s)</h6>
            <p>
                @foreach($resource->authors as $author)
                    @if($author->is_lead && has_profile($author->username))
                        <a href="{{ route('account.profile', $author->username) }}">
                            {{ $author->fullname }}
                        </a> |
                    @else
                        <span>{{ $author->fullname }} |</span>
                    @endif
                @endforeach
            </p>
        </li>

        <!-- Remaining fields -->
     

        <li>
            <h6>Resource Type</h6>
            <a href="{{ route('resources.types.show', $resource->type) }}" class="capitalize">
                {{ $resource->type }}
            </a>
        </li>

        <li>
            <h6>Reviews</h6>
            <p>{{ count($resource->reviews) }}</p>
        </li>

        <li>
            <h6>Views</h6>
            <p>{{ $resource->view_count }}</p>
        </li>

    </ul>
</div>
