<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return void
     */

    public function prepareForValidation(): void
    {
        $this->merge([
            'code' => Str::upper(str_replace(' ', '', $this->code)),
            'conference_id' =>$this->header('X-Conference-Id')
        ]);
    }


    public function rules(): array
    {

        return [
            'code' => ['required'],
            'discount_value' => ['required'],
            'discount_type' => ['required'],
            'coupon_type' => ['required'],
            'count' => ['required'],
            'active' => ['boolean'],
            'conference_id' => ['required'],
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}

