<?php

namespace App\Modules\Account\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBankInformationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'bank_name'       => 'nullable',
            'bank_account_name'         => 'nullable',
            'bank_account_number'        => 'nullable',

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
