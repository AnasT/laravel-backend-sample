<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
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
        $emailUniqueRule = Rule::unique('subscribers');

        if ($this->subscriber) {
            $emailUniqueRule = $emailUniqueRule->ignoreModel($this->subscriber);
        }

        return [
            'email' => [
                'required',
                'email:rfc,dns',
                $emailUniqueRule->where('user_id', $this->user()->id),
            ],
            'name' => [
                'required',
            ],
            'fields.*.id' => [
                Rule::exists('fields', 'id')->where('user_id', $this->user()->id),
            ],
            'fields.*.value' => []
        ];
    }
}
