<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AcademyRegisterRequest extends FormRequest
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
            'name' => ['required'],
            'nim' => ['required', 'min:10', 'max:12'],
            'email' => ['required', 'email'],
            'phone_number' => ['required'],
            'document' => ['required'],
            'gender' => ['required'],
            'year_of_enrollment' => ['required', 'date_format:Y'],
            'faculty' => ['required'],
            'major' => ['required'],
            'class' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'errors' => $validator->getMessageBag()
        ]), 400);
    }
}
