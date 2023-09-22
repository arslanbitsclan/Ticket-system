<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => ['required', 'string','max:255', Rule::unique('users')->where(function ($query) {return $query->whereNull('deleted_at');})],
            'phone' => 'required|min:7|max:12',
            'password' => 'required|min:4|',
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'role' => 'required'  
        ];

    }
}
