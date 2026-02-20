<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'context' => 'sometimes|string',
            'chosen_option' => 'sometimes|string|max:255',
            'confidence_score' => 'sometimes|integer|min:0|max:100',
            'review_date' => 'sometimes|date|after_or_equal:today',
        ];
    }
}