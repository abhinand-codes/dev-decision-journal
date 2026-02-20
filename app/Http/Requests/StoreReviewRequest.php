<?php

namespace App\Http\Requests;

use App\Models\Assumption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // FIX: scope assumption_id validation to assumptions belonging to THIS decision
        $decisionId = $this->route('decision')?->id;

        return [
            'was_successful' => 'required|boolean',
            'outcome_notes' => 'nullable|string',

            'assumption_evaluations' => 'nullable|array',
            'assumption_evaluations.*.assumption_id' => [
                'required',
                Rule::exists('assumptions', 'id')->where('decision_id', $decisionId),
            ],
            'assumption_evaluations.*.was_correct' => 'required|boolean',
        ];
    }
}