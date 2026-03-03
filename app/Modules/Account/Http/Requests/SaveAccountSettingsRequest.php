<?php

namespace App\Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class SaveAccountSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {   

        return [
            'address'       => ['nullable', 'string'],
            'phone'         => ['nullable', 'string'],
            'phone1'        => ['nullable', 'string'],
            'twitter'       => ['nullable', 'string'],
            'facebook'      => ['nullable', 'string'],
            'linkedin'      => ['nullable', 'string'],
            'dob'           => 'before:'.\Carbon\Carbon::now().'|before:18 years ago|',
            'gender'        => ['nullable', 'string'],
            'biography'     => ['nullable', 'string'],
            'interest'      => ['nullable', 'string'],
 
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
