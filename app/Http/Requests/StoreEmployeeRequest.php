<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'nullable|string|max:100',
            'email'         => 'nullable|email|unique:employees,email',
            'date_of_birth' => 'nullable|date',
            'designation'   => 'nullable|string|max:100',
            'department_id' => 'required|exists:departments,id',

            // Phone numbers
            'phone_numbers'              => 'nullable|array',
            'phone_numbers.*.phone'      => 'required|string|max:20',
            'phone_numbers.*.label'      => 'nullable|string|max:50',
            'phone_numbers.*.is_primary' => 'boolean',

            // Addresses
            'addresses'                   => 'nullable|array',
            'addresses.*.line1'           => 'required|string|max:255',
            'addresses.*.line2'           => 'nullable|string|max:255',
            'addresses.*.city'            => 'nullable|string|max:100',
            'addresses.*.state'           => 'nullable|string|max:100',
            'addresses.*.country'         => 'nullable|string|max:100',
            'addresses.*.postal_code'     => 'nullable|string|max:20',
            'addresses.*.label'           => 'nullable|string|max:50',
            'addresses.*.is_primary'      => 'boolean',
        ];
    }
}
