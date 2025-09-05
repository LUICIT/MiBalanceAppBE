<?php

namespace App\Http\Requests\Periods;

use Illuminate\Foundation\Http\FormRequest;

class PeriodUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string'],
            'type_period' => ['required', 'string', 'max:75'],
            'payment_date' => ['required', 'date_format:Y-m-d'],
            'notes' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('validation.required', ['attribute' => 'code']),
            'code.string' => __('validation.string', ['attribute' => 'code']),
            'type_period.required' => __('validation.required', ['attribute' => 'type_period']),
            'type_period.string' => __('validation.string', ['attribute' => 'type_period']),
            'payment_date.required' => __('validation.required', ['attribute' => 'payment_date']),
            'payment_date.date_format' => __('validation.date_format', ['attribute' => 'payment_date', 'format' => 'YYYY-mm-dd']),
            'notes.string' => __('validation.string', ['attribute' => 'notes']),
        ];
    }
}
