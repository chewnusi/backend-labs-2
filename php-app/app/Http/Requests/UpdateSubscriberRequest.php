<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberRequest extends FormRequest
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
        $subscriberId = $this->route('subscriber')->id;

        return [
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('subscribers', 'email')->ignore($subscriberId),
            ],
            'name' => 'sometimes|required|string|max:255',
        ];
    }
}
