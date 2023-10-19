<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AcademyUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required'],
            'nim' => ['sometimes', 'required', 'min:10', 'max:12'],
            'email' => ['sometimes', 'required', 'email'],
            'phone_number' => ['sometimes', 'required'],
            'document' => ['sometimes', 'required'],
            'gender' => ['sometimes', 'required'],
            'year_of_enrollment' => ['sometimes', 'required', 'date_format:Y'],
            'faculty' => ['sometimes', 'required'],
            'major' => ['sometimes', 'required'],
            'class' => ['sometimes', 'required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ]), 400);
    }
}
