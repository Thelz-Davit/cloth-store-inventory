<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        $emailRule = Rule::unique('users', 'email');
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $emailRule = $emailRule->ignore($id, 'id');
        }

        return [
            'name'    => 'required|max:120',
            'email'   => ['required', 'email', $emailRule],
            'role_id' => 'required|integer',
            'address' => 'nullable',
            'phone'   => 'nullable|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name required',
            'name.max' => 'Name maximum 120 characters',
            'email.required' => 'Email required',
            'email.email' => 'Email format is not valid',
            'email.unique' => 'Email already exists',
            'role_id.required' => 'Role required',
            'phone.max' => 'Phone maximum 30 characters',
        ];
    }
}
