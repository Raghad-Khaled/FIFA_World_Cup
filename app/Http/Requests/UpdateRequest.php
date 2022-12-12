<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name' => 'nullable|max:255',
            'last_name' => 'nullable|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:f,m',
            'nationality' => 'nullable|string',
            'password' => 'nullable|min:6',
        ];
    }
}
