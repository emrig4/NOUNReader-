<?php

namespace App\Modules\Resource\Http\Livewire\Resources;

use Livewire\Component;
use App\Modules\Resource\Models\Resource as ResourceModel;
use Livewire\WithPagination;
use Log;


class SearchResult extends Component
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
    public $min_keyword_length = 2; // Minimum characters for keyword search

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
        $this->subfield = request()->query('subfield');
        $this->type = request()->query('type');

        $query = $this->loadQuery();
        $this->loadList($query);
    }

    public function hydrate(){
    	$query = $this->loadQuery();
        $this->loadList($query);
       
    }
    
    public function loadQuery(){
        $this->loading_message = "Searching...";
        $field = $this->field;
        $search = trim($this->search);
        $subfield = $this->subfield;
        $type = $this->type;
        $filter = $this->filter;
        
        $query = ResourceModel::query();
        $searchApplied = false;

        // Two-stage search: Exact match first, then keyword partial matching
        if ($search) {
            $keywords = $this->parseSearchKeywords($search);
            $validKeywords = array_filter($keywords, function($k) {
                return strlen($k) >= $this->min_keyword_length;
            });
            
            if (!empty($validKeywords)) {
                $searchApplied = true;
                
                // Stage 1: Check for exact title match first
                $exactMatchQuery = ResourceModel::where('title', 'LIKE', '%' . $search . '%')
                    ->where('is_published', 1);
                
                // Apply filters to exact match query
                if ($type) {
                    $exactMatchQuery->where('type', $type);
                }
                if ($subfield) {
                    $exactMatchQuery->where('sub_fields', 'like', '%' . $subfield . '%');
                }
                if ($field) {
                    $exactMatchQuery->where('field', $field);
                }
                
                $exactMatches = $exactMatchQuery->get();
                
                if ($exactMatches->count() > 0) {
                    // Use exact matches
                    $query->whereIn('id', $exactMatches->pluck('id')->toArray());
                } else {
                    // Stage 2: No exact matches - use keyword partial matching on title
                    $query->where(function($mainQ) use ($validKeywords) {
                        foreach ($validKeywords as $keyword) {
                            $likePattern = '%' . $keyword . '%';
                            $mainQ->orWhere('title', 'like', $likePattern);
                        }
                    });
                }
            }
        }

        // Apply type filter
        if ($type) {
            $query->where('type', $type);
        }
        
        // Apply subfield filter
        if ($subfield) {
            $query->where('sub_fields', 'like', '%' . $subfield . '%');
        }
        
        // Apply field filter
        if ($field) {
            $query->where('field', $field);
        }
        
        // If no search was applied and no filters, limit to recent resources
        if (!$searchApplied && !$type && !$subfield && !$field) {
            $query->where('is_published', 1)->orderBy('created_at', 'desc')->limit(100);
        }
        
        return $query;
    }

    /**
     * Parse search query into individual keywords
     * Handles various separators: space, comma, semicolon
     *
     * @param string $search
     * @return array
     */
    protected function parseSearchKeywords($search){
        // Replace common separators with spaces
        $search = str_replace([',', ';', '|', '/'], ' ', $search);
        
        // Split into keywords and remove empty entries
        $keywords = array_filter(array_map('trim', explode(' ', $search)));
        
        // Remove very short keywords (less than 2 characters)
        $keywords = array_filter($keywords, function($keyword) {
            return strlen($keyword) >= $this->min_keyword_length;
        });
        
        return array_values($keywords);
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
        
        $this->loading_message = "Searching...";
        $filter = $this->filter;

        $query = ResourceModel::query();
        $searchApplied = false;

        // Two-stage search: Exact match first, then keyword partial matching
        if (!empty($filter["title"])) {
            $keywords = $this->parseSearchKeywords($filter["title"]);
            $validKeywords = array_filter($keywords, function($k) {
                return strlen($k) >= $this->min_keyword_length;
            });
            
            if (!empty($validKeywords)) {
                $searchApplied = true;
                $searchTerm = $filter["title"];
                
                // Stage 1: Check for exact title match first
                $exactMatchQuery = ResourceModel::where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->where('is_published', 1);
                
                // Apply filters to exact match query
                if (!empty($filter["field"])) {
                    $exactMatchQuery->where('field', 'like', '%' . $filter["field"] . '%');
                }
                if (!empty($filter["subfield"])) {
                    $exactMatchQuery->where('sub_fields', 'like', '%' . $filter["subfield"] . '%');
                }
                
                $exactMatches = $exactMatchQuery->get();
                
                if ($exactMatches->count() > 0) {
                    // Use exact matches
                    $query->whereIn('id', $exactMatches->pluck('id')->toArray());
                } else {
                    // Stage 2: No exact matches - use keyword partial matching on title
                    $query->where(function($mainQ) use ($validKeywords) {
                        foreach ($validKeywords as $keyword) {
                            $likePattern = '%' . $keyword . '%';
                            $mainQ->orWhere('title', 'like', $likePattern);
                        }
                    });
                }
            }
        }

        // Ordering
        if (!empty($filter["order_field"])) {
            $order_type = (!empty($this->filter["order_type"])) ? $this->filter["order_type"] : 'DESC';
            $query->orderBy($filter["order_field"], $order_type);
        }

        if (!empty($filter["field"])) {
            $query->where('field', 'like', '%' . $filter["field"] . '%');
        }

        if (!empty($filter["subfield"])) {
            $query->where('sub_fields', 'like', '%' . $filter["subfield"] . '%');
        }

        // If no search applied, limit results to prevent showing everything
        if (!$searchApplied && empty($filter["field"]) && empty($filter["subfield"])) {
            $query->where('is_published', 1)->orderBy('created_at', 'desc')->limit(100);
        }

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
        return view('resource::livewire.resources.search-result',['resources' => $resources]);
    }
}