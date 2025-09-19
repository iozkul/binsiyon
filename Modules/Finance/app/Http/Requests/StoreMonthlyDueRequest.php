<?php

namespace Modules\Finance\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyDueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy handles authorization
    }

    public function rules(): array
    {
        return [
            'site_id' => 'required|exists:sites,id',
            'apartment_id' => 'required|exists:apartments,id',
            'resident_user_id' => 'required|exists:users,id',
            'period' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,partially_paid',
        ];
    }
}
