<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlayerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required_unless:name_random,on',
            'birth_year' => 'required_unless:birth_year_random,on|integer|min:0|max:99999',
            'gender' => 'required_unless:gender_random,on',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required_unless' => '名前を入力してください。',
            'birth_year.required_unless' => '生年を選択してください。',
            'birth_year.integer' => '生年は整数で入力してください。',
            'birth_year.min' => '生年は0以上の整数で入力してください。',
            'birth_year.max' => '生年は99999以下の整数で入力してください。',
            'gender.required_unless' => '性別を選択してください。',
        ];
    }
}
