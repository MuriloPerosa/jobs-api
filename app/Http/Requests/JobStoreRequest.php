<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobStoreRequest extends FormRequest
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
            'title'        => ['string', 'required', 'max:100'],
            'description'  => ['string', 'required', 'max:500'],
            'is_complete'  => ['bool', 'required'],
            'user_id'      => ['integer', 'required', 'exists:users,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        /**
         * Only managers can create jobs for other users
         */
        $this->merge([
            'user_id' => auth()->user()->role != 'MANAGER' ? auth()->user()->id : $this->user_id,
        ]);
    }
}
