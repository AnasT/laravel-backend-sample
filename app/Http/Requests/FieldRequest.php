<?php

namespace App\Http\Requests;

use App\FieldType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $nameUniqueRule = Rule::unique('fields');

        if ($this->field) {
            $nameUniqueRule = $nameUniqueRule->ignoreModel($this->field);
        }

        return [
            'name' => [
                'required',
                $nameUniqueRule->where('user_id', $this->user()->id),
            ],
            'type' => !$this->field ? [
                'required',
                Rule::in(FieldType::ALLOWED_TYPES),
            ] : [],
        ];
    }
}
