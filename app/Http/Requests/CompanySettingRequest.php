<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompanySettingRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'hotline' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admin_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'admin_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'auth_bg' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_keyword' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:5000'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'google_site_verification' => ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-]+$/'],
            'gtag' => ['nullable', 'required_if:analytics_enabled,1', 'string', 'max:255', 'regex:/^G-[A-Z0-9]+$/'],
            'analytics_enabled' => ['required', 'boolean'],
            'search_indexing_enabled' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' => 'The company name field is required.',
            'company_email.required' => 'The company email field is required.',
            'company_email.email' => 'The company email must be a valid email address.',
            'company_phone.required' => 'The company phone field is required.',
            'company_address.required' => 'The company address field is required.',
            'logo.image' => 'The logo must be an image file.',
            'favicon.image' => 'The favicon must be an image file.',
            'admin_logo.image' => 'The admin logo must be an image file.',
            'admin_favicon.image' => 'The admin favicon must be an image file.',
            'auth_bg.image' => 'The authentication background must be an image file.',
        ];
    }
}
