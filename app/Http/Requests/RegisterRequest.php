<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username'=>'required|unique:users|max:255',
            'first_name'=>'required|max:255',
            'last_name'=>'required|max:255',
            'birth_date'=>'required|date',
            'gender'=>'required|in:f,m',
            'nationality'=>'string|nullable',
            'role'=>'required|in:manager,fan',
            'email'=>'required|unique:users|email',
            'password'=>'required|min:6',
        ];
    }
}
