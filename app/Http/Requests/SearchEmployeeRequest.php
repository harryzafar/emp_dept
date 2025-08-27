<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchEmployeeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Or add authorization logic
    }

    public function rules()
    {
        return [
            'first_name'    => 'nullable|string|max:100',
            'last_name'     => 'nullable|string|max:100',
            'email'         => 'nullable|email',
            'position'      => 'nullable|string|max:100',
            'department_id' => 'nullable|exists:departments,id',
            'phone'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
        ];
    }
}
