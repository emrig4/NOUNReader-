<h2 class="ereaders-section-heading">Related Project Guild</h2>
<div class="ereaders-books ereaders-book-grid ereaders-book-related">
    <div class="row">
        <!-- FIXED: Limit to exactly 6 related resources -->
        @foreach($related->take(3) as $resource)
        <div class="col-md-4">
            
           


            <div class="ereaders-blog-grid-wrap">
                <div class="ereaders-blog-grid-text">
                    <div class="flex justify-between mb-4">
                        <span>{{ $resource->field }}</span>
                        @if( $resource->isNew() )
                            <span class="badge badge-primary my-auto" style="color: white; font-size: 10px">NEW</span>
                        @elseif( $resource->isTop() )
                            <span class="badge badge-warning my-auto" style="color: white; font-size: 10px">TOP</span>
                        @endif
                    </div>
                    <h2><a href="{{ route('resources.show', $resource->slug ) }}">{{ $resource->title }}</a></h2>
                    
                    <!-- Related Resource Image -->
                    <div class="ereaders-resource-image mb-3">
                        <a href="{{ route('resources.show', $resource->slug ) }}">
                            <img src="{{ asset('themes/airdgereaders/images/Nounreader.webp') }}" 
                                 alt="{{ $resource->title }}" 
                                 class="img-fluid rounded"
                                 style="max-width: 100%; height: auto; border-radius: 8px;">
                        </a>
                    </div>
                    
                    <div class="ereaders-blog-heading">
                        <ul class="ereaders-thumb-option flex">
                            <li class="text-xxs">Author: 
                                <a href="{{ route('account.profile',  $resource->author()->username ) }}" class="text-xxs" >{{ $resource->author()->fullname }}</a>
                            </li>
                           <!--  <li class="text-xxs">Reviews:
                                <a href="#" class="text-xxs" >{{ $resource->reviews()->count() }}</a>
                            </li> -->
                            <li class="text-xxs">Type:
                                <a href="#" class="text-xxs" >{{ $resource->type }}</a>
                            </li>
                        </ul>
                    </div>
                    <p>{{ $resource->description }}</p>
                    <a href="{{ route('resources.show', $resource->slug ) }}" class="btn btn-primary ereaders-readmore-btn">Download</a>
                    @if(auth()->user() && $resource->user_id == auth()->user()->id)
                        <a href="{{ route('resources.edit', $resource->slug ) }}" class="btn btn-warning ereaders-readmore-btn"><i class="fa fa-edit text-white" ></i></a>
                   
                   
                        <a href="{{ route('resources.show', $resource->slug ) }}" class="btn btn-danger ereaders-readmore-btn"><i class="fa fa-trash text-white"></i></a>
                    @endif
                </div>
            </div>





        </div>
        @endforeach
    </div>
    
    <!-- Show message if there are fewer than 6 related resources -->
    @if($related->count() == 0)
        <div class="col-md-12">
            <div class="text-center" style="padding: 40px; color: #666;">
                <p>No related resources found</p>
            </div>
        </div>
    @endif
</div>

