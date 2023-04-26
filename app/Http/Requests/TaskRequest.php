<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
                         'user_id' => auth()->id()
                     ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required',
                        'min:' . config('laratasks.min_title_length'),
                        'max:255',
                        Rule::unique('tasks', 'title')->ignore($this->task)],
            'user_id' => ['required', 'exists:users,id']
        ];
    }

    protected function passedValidation(): void
    {
        $this->replace(['title' => strtolower($this->title)]);
    }
}
