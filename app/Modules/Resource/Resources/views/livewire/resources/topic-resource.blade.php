<div class="">   
	<style>
        .table p{
            margin: 0;
        }

        .filter-container{
            margin-bottom: 15px;
            padding: 5px;
            background: white;
            color: black;
            font-size: 16px;
        }

        .filter-container > .row{
            margin: 0;
        }

        .filter-container > .row > div{
            padding: 0 5px;
        }

       .filter-container  input[type="text"] {
		    min-height: 28px !important;
            font-size: 14px;
            background: white;
		}

        .filter-container .form-control {
            padding: 6px 12px;
            color: #9ca3af;
            border: 1px solid #cccccc69;
        }

    </style>

	
	<div class="clearfix mb-10"></div>
	<div wire:loading >
	  	{{ $loading_message }}
	</div>
    

	<div class="filter-container">
        <div class="flex justify-around">
            <div class="md:w-2/3">
                <input wire:change="$emit('filtered')" placeholder="Filter by title" type="text" class="form-control" wire:model="filter.title"   >
            </div>


            <div class="md:w-1/3">
                <select wire:model="items_per_page"  wire:change="$emit('filtered')" class="form-control" >
                    <option value="">perpage</option>
                    <option value="">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
            </div>

            <div class="" style="display: flex;align-items: flex-end;" >
                <div>
                    <button type="button" wire:click="filterList" class="btn btn-primary" >Filter</button>
                </div>
            </div>
        </div>
	</div>


	@if(!empty($objects))
	<div class="ereaders-blog ereaders-blog-grid">
	    <ul class="row">
	        @foreach($objects as $resource)
	            <li class="col-md-4">
	                @include('resource.partials.inc.grid_card', $resource)
	            </li>
	        @endforeach
	    </ul>
	</div>
	@endif

	<!--// Pagination \\-->
	<div class="ereaders-pagination">
	     {!! $resources->links('partials.pagination') !!}
	</div>
	<!--// Pagination \\-->
</div>


