<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAdminRequest extends FormRequest
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
            'username'=>'required|unique:administrators|max:255',
            'first_name'=>'required|max:255',
            'last_name'=>'required|max:255',
            'email'=>'required|unique:administrators|email',
            'password'=>'required|min:6',
        ];
    }
}
