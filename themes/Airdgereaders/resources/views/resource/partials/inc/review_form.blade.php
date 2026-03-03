 <!--// comment-respond \\-->
  <div class="comment-respond">
     <h2 class="ereaders-section-heading">LEAVE a review</h2>
     @if ( $errors->any() )
     <p class="mt-10  text-sm leading-5 text-gray-500">
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </p>
    @endif
     <form action="{{ route('reviews.store', ['slug' => $resource->slug ])  }}" method="POST" class="text-lg" >
         @method('POST')
         @csrf
        <p class="ereaders-full-form">
           <textarea required name="comment" placeholder="Comment"></textarea>
           <i class="text-lg fa fa-pencil-square-o"></i>
        </p>
        <p>
           <input type="text" value="{{ auth()->user() ?  auth()->user()->name : '' }}" name="name">
           <i class="fa fa-user"></i>

            <input class="hidden" type="text" hidden name="user_id" value="{{ auth()->user() ? auth()->user()->id : '' }}">

            <input class="hidden" type="text" hidden name="resource_id" value="{{ $resource->id }}">

        </p>
        <p>
           <select name="rating" id="" class="form-control h-12">
               <option value="">Select Rating</option>
               <option value="10">1</option>
               <option value="20">2</option>
               <option value="30">3</option>
               <option value="40">4</option>
               <option value="50">5</option>
           </select>
        </p>

        <p><input type="submit" value="Submit Now" class="submit h-12"></p>
     </form>
  </div>
  <!--// comment-respond \\-->

