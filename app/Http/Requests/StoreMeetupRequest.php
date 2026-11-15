<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location' => ['required', 'string', 'max:180'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
