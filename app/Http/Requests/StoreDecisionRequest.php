<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDecisionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'context' => 'required|string',
            'chosen_option' => 'required|string|max:255',
            'confidence_score' => 'required|integer|min:0|max:100',
            'review_date' => 'required|date|after_or_equal:today',

            'options' => 'required|array|min:2',
            'options.*.label' => 'required|string|max:255',
            'options.*.pros' => 'nullable|string',
            'options.*.cons' => 'nullable|string',

            'assumptions' => 'nullable|array',
            'assumptions.*.description' => 'required|string',

            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ];
    }
}