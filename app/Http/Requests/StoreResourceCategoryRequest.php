<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:resource_categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'approval_type' => ['required', 'string', Rule::in(['NONE', 'MANAGER', 'ADMIN'])],
        ];
    }
}
