<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

            // FIX: add tag_ids, options and assumptions support for PATCH
            'tag_ids' => 'sometimes|array',
            'tag_ids.*' => 'integer|exists:tags,id',

            'options' => 'sometimes|array|min:2',
            'options.*.label' => 'required_with:options|string|max:255',
            'options.*.pros' => 'nullable|string',
            'options.*.cons' => 'nullable|string',

            'assumptions' => 'sometimes|array',
            'assumptions.*.description' => 'required_with:assumptions|string',
        ];
    }
}