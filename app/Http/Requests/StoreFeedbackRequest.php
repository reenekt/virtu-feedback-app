<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fullname' => ['required', 'string', 'min:2', 'max:255'],
            'contact_phone' => ['required', 'string', Rule::phoneE164()], // Rule::phoneE164 defined in AppServiceProvider
        ];
    }
}
