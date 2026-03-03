<div class="ereaders-service ereaders-service-grid">
    <ul class="row">
        @foreach($featuredResourceFields as $field)
        <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px ">
            <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px">
                <h6><a href="{{ route('resources.fields.show', $field->slug) }}">{{ $field->title }}</a></h6>
                <p>{{$field->subfields->count()}} subfields</p>
            </div>
        </li>
        @endforeach
    </ul>
</div>
