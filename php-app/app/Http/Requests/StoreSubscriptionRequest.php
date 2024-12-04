<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Змінити на true, якщо немає додаткової авторизації
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subscriber_id' => 'required|exists:subscribers,id',
            'service' => 'required|string|max:255',
            'topic' => 'required|string|max:255',
            'payload' => 'nullable|json',
            'expired_at' => 'nullable|date',
        ];
    }
}
