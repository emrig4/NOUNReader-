@php
@endphp
<div class="ereaders-blog ereaders-blog-grid">
    <ul class="row">
        @foreach($posts as $post)
        <li class="col-md-4">
            <div class="ereaders-blog-grid-wrap">
                <figure>
                    <?=$post->image_tag("medium", true, ''); ?>
                </figure>
                <div class="ereaders-blog-grid-text">
                    <span>{{$post->category}}</span>
                    <h2><a href="{{ route('blog.show', $post->slug) }}">{{$post->title}}</a></h2>
                    <ul class="ereaders-blog-option">
                        <li>{{date('d M Y ', strtotime($post->post->posted_at))}}</li>
                        <li>{{$post->post->author->name}} </li>
                    </ul>
                    <p>{!! mb_strimwidth($post->post_body_output(), 0, 200, "...") !!}</p>
                    <a href="{{ route('blog.show', $post->slug)  }}" class="ereaders-readmore-btn">Read more <i class="fa fa-angle-double-right"></i></a>
                </div>
            </div>
        </li>
        @endforeach
        
    </ul>
</div>

