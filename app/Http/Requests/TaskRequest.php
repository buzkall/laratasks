<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
                         'new_field' => 1
                     ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3',
                Rule::unique('tasks', 'title')->ignore($this->task)]
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace(['title' => strtolower($this->title)]);
    }
}
