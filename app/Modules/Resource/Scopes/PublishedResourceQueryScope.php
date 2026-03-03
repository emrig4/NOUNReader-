<?php 

	namespace App\Modules\Resource\Scopes
	
	class PublishedResourceScope implements Scope {
		public function apply(Builder $builder, Model $model)
	    {
	        return $builder->where('is_published');
	    }

	}