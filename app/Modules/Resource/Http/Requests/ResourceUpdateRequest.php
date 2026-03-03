<?php

namespace App\Modules\Resource\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use App\Modules\Resource\Models\Resource;



class ResourceUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug' => $this->getSlugRules(),
            'title' => 'required',
            'overview' => 'required',
            'publication_year' => 'nullable|integer|min:1900',
            
            'coauthors' => 'nullable', //comma delimited strings
            'author' => 'nullable', // string
            'type' => 'required', // resource field slug
            'field' => 'required', //  resource field slug
            'sub_fields' => 'required', //comma delimited strings

            'currency' => 'nullable',
            // 'price' => [ new RequiredIf($this->price != null ), 'integer'],
            'price' => new RequiredIf($this->currency != null ),

            'page_count' => 'nullable|integer',
            'preview_limit' => 'nullable|integer',
            'isbn' => 'nullable',
            'is_featured' => 'nullable',
            'is_private' => 'nullable',
            'is_active' => 'nullable',
        ];
    }

     private function getSlugRules()
    {
        // Get the resource being updated (if any)
        $resourceId = null;
        
        // Try to get the resource from the route
        if ($this->route('slug')) {
            $resource = Resource::where('slug', $this->route('slug'))->first();
            if ($resource) {
                $resourceId = $resource->id;
            }
        }
        
        // Also check for resource ID in the route parameters
        if (!$resourceId && $this->route('resource')) {
            $resourceId = $this->route('resource')->id;
        }
        
        // If we have a resource ID, ignore it in the unique rule (for updates)
        // Otherwise, just use standard unique rule (for create)
        if ($resourceId) {
            $rules[] = Rule::unique('resource', 'slug')->ignore($resourceId);
        } else {
            $rules[] = Rule::unique('resource', 'slug');
        }
        
        return $rules;
    }
}
