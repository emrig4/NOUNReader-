<?php

namespace App\Modules\Resource\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;



class ResourceStoreRequest extends FormRequest
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
            'type' => 'required', // resource field slug
            'field' => 'required', //  resource field slug
            'sub_fields' => 'nullable', //comma delimited strings - made nullable for submit form

            'currency' => 'nullable',
            // 'price' => [ new RequiredIf($this->price != null ), 'integer'],
            'price' => new RequiredIf($this->currency_id != null ),

            'page_count' => 'nullable|integer',
            'preview_limit' => 'nullable|integer',
            'isbn' => 'nullable',
            'is_featured' => 'nullable',
            'is_private' => 'nullable',
            'is_active' => 'nullable',
            'author' => 'nullable',
            'coauthors' => 'nullable',
            
            // File upload validation
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:51200', // 50MB max
            'tmpfile_name' => 'nullable|string',
        ];
    }

     private function getSlugRules()
    {
        $rules[] = Rule::unique('resource', 'slug');
        return $rules;
    }
}

