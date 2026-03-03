<?php

namespace App\Modules\Resource\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateResourceReviewRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'resource_id' => 'required',
            'user_id'  => 'required',
            'name'  => 'nullable',
            'rating'  => 'nullable',
            'comment'  => 'required',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
