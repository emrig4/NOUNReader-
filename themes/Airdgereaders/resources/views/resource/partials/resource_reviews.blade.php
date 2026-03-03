<div class="comments-area ereaders-book-reply">
  <!--// coments \\-->
  <h2 class="ereaders-section-heading">Book Reviews</h2>
  <ul class="comment-list">
    @foreach($resource->reviews as $review)
     <li>
        <div class="thumb-list">
         @if($review->user && $review->user->profile_photo_url)
    <img alt="" src="{{ $review->user->profile_photo_url }}">
@else
    <img alt="" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4K..." alt="Default Avatar">
@endif</figure>
           <div class="text-holder">
            <h6>{{ $review->name }}</h6>
            <time class="post-date" datetime="2008-02-14 20:00">{{ ($review->created_at)->diffForHumans() }}</time><br>
            <div class="star-rating"><span class="star-rating-box" style="width: {{$review->rating   }}%"></span></div>
              <p>{{ $review->comment }}</p>
           </div>
        </div>
     </li>
    @endforeach
  </ul>
  <!--// coments \\-->
  @include('resource.partials.inc.review_form', [ 'resource' => $resource ])
</div>
