@push('css')
        <style>
            .subfields .widget_cetagories ul li a:before {
                display: none;
            }
        </style>
@endpush
<div class="subfields" >
   <div class="bg-white widget widget_cetagories widget_border">
        <ul class="" style="list-style:none;">
            @foreach($subfields  as $subfield)
            <li class="" >
                <a href="{{ route('resources.topics.show', $subfield->slug) }}">
                    {{ $subfield->title }} </a>
                <!-- <span>{{ $subfield->resourceCount() }}</span></a> -->
                <!-- <span>{{ random_int(00, 99) }}</span></a> -->
            </li>
            @endforeach
        </ul>
    </div>
</div>

