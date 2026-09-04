<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResourceCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        $categoryId = $this->route('resource_category')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('resource_categories')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'approval_type' => ['required', 'string', Rule::in(['NONE', 'MANAGER', 'ADMIN'])],
        ];
    }
}
