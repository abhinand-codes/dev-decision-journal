<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'was_successful' => 'required|boolean',
            'outcome_notes' => 'nullable|string',

            'assumption_evaluations' => 'nullable|array',
            'assumption_evaluations.*.assumption_id' => 'required|exists:assumptions,id',
            'assumption_evaluations.*.was_correct' => 'required|boolean',
        ];
    }
}