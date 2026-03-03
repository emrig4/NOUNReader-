<?php

namespace App\Modules\Resource\Http\Livewire\Resources;

use Livewire\Component;
use App\Modules\Resource\Models\Resource as ResourceModel;
use Livewire\WithPagination;
use Log;


class TopicResource extends Component
{

	use WithPagination;

	protected $paginationTheme = 'tailwind';

    public $objects = []; 

    public $count = 0;

    public $page = 1;

    public $items_per_page = 12;

    public $loading_message = "";

    public $listeners = [
        "filtered" => "filterList"
    ];

    public $search = '';
    public $field = '';
    public $subfield = '';
    public $type = '';

    protected $updatesQueryString = ['page'];

    public $filter = [
        "title" => "",
        "field" => "",
        "subfield" => "",
        "order_type" => "",
    ];

    public function mount(){

    	$this->field = request()->query('field');
        $this->search = request()->query('search');
        $this->subfield = request()->query('subfield') ?? request()->slug;
        $this->type = request()->query('type');

        $query = $this->loadQuery();
        $this->loadList($query);
    }

    public function hydrate(){
    	$query = $this->loadQuery();
        $this->loadList($query);
       
    }
    
    public function loadQuery(){
        $this->loading_message = "Loading Request...";
        $field = $this->field;
        $search = $this->search;
        $subfield = $this->subfield;
        $type = $this->type;
        $filter = $this->filter;
        $query = ResourceModel::where(function($q)use($search, $subfield, $type, $field, $filter){
            if($search){
                $q->where('title', 'like', '%' . $search .'%');
            }
            if($type){
                $q->where('type', $type);
                
            }
            if($subfield){
                $q->whereIn('sub_fields',  [$subfield]);
            }

     	});
       return $query;
    }
    public function loadList($query){
        // count
        $this->count = $query->count();

        // Paginating
        $objects = $query->paginate($this->items_per_page);
        $this->paginator = $objects->toArray();
        $this->objects = $objects->items();
    }


    public function filterList(){
    	$this->resetPage();
    	
        $this->loading_message = "Loading Request...";
        // $query = [];
        $filter = $this->filter;

        $query = ResourceModel::where(function($q)use($filter){

            // Filter Search
	        if(!empty($filter["title"])){
	            $q->where('title', 'LIKE', '%' . $filter['title'] . '%');
	        }

	        // Ordering
	        if(!empty($filter["order_field"])){
	            $order_type = (!empty($this->filter["order_type"]))? $this->filter["order_type"]: 'ASC';
	           $q->orderBy($filter["order_field"], $order_type);
	        }

	        if(!empty($filter["field"])){
            	$q->where('field', 'LIKE', '%' . $filter["field"] . '%');
        	}

        	if(!empty($filter["subfield"])){
            	 $q->whereIn('sub_fields',  [ $filter["subfield"] ] );

        	}

     	});

        $this->count = $query->count();
        $objects = $query->paginate($this->items_per_page);
        $this->paginator = $objects->toArray();
        $this->objects = $objects->items();
    }

    // Pagination Method
    public function applyPagination($action, $value = null, $options=[]){
        if( $action == "previous_page" && $this->page > 1){
            $this->page-=1;
        }
        if( $action == "next_page" ){
            $this->page+=1;
        }

        if( $action == "page" ){
            $this->page=$value;
        }
        // $this->loadList();
    }

    public function updatesQueryString(){

    }
    public function render()
    {	
    	$resources =  $this->loadQuery()->paginate($this->items_per_page);
        return view('resource::livewire.resources.topic-resource',['resources' => $resources]);
    }
}
