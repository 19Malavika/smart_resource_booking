<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:resources,code'],
            'category_id' => ['required', 'exists:resource_categories,id'],
            'manager_id' => ['nullable', 'exists:users,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'string', Rule::in(['AVAILABLE', 'UNAVAILABLE', 'MAINTENANCE', 'INACTIVE'])],
            'approval_type' => ['required', 'string', Rule::in(['NONE', 'MANAGER', 'ADMIN'])],
        ];
    }
}
