<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|max:255',
            'mobile' => 'required|string|max:20',
            'username' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The Name field is required.',
            'email.required' => 'The Email field is required.',
            'mobile.email' => 'The mobile must be a valid email address.',
            'username.required' => 'The Username field is required.',
            'address.required' => 'The address field is required.',
        ];
    }
}
