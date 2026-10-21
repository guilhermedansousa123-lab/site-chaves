<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLoanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $required = $this->isMethod('PUT') ? 'required' : 'sometimes';

        return [
            'key_id' => [$required, 'integer', 'exists:keys,id'],
            'person' => [$required, 'string', 'max:80'],
            'note' => [$required, 'nullable', 'string', 'max:120'],
        ];
    }
}
