<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'priority_id' => ['required', Rule::exists('priorities', 'id')],
            'status_id' => ['required', Rule::exists('status', 'id')],
            'department_id' => ['required', Rule::exists('departments', 'id')],
            'assigned_to' => ['required', Rule::exists('users', 'id')],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'type_id' => ['required', Rule::exists('types', 'id')],
            'subject' => ['required'],
            'details' => ['required'],
        ];
    }
}
