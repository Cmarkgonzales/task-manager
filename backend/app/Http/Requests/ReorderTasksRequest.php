<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTasksRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Add authorization logic if needed per-user or admin
        return true;
    }

    public function rules(): array
    {
        return [
            'ordered_ids'   => ['required', 'array', 'min:1'],
            'ordered_ids.*' => ['integer', 'exists:tasks,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ordered_ids.required' => 'The ordered_ids field is required.',
            'ordered_ids.array'    => 'The ordered_ids field must be an array of task IDs.',
            'ordered_ids.*.exists' => 'One or more task IDs do not exist.',
        ];
    }
}

