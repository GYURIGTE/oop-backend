<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMeetupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['Accepted', 'Declined', 'Completed', 'Cancelled'])],
            'location' => ['sometimes', 'string', 'max:180'],
            'scheduled_at' => ['sometimes', 'date'],
            'note' => ['sometimes', 'nullable', 'string', 'max:500'],
        ];
    }
}
