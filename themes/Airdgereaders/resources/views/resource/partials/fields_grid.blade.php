    <!--// Todo \\-->
<div class="ereaders-book-detail">
    <ul>
    	@foreach($resourceFields as $field)
    		<a href="{{ route('resources.fields.show', $field->slug) }}">
	    		<li class="border">
		            <h6>{{$field->title}}</h6>
		            <p>{{$field->subfields->count()}} subfields</p>
	        	</li>
	        </a>
    	@endforeach
    </ul>
</div>

