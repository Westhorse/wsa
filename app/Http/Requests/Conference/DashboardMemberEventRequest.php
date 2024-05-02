<?php

namespace App\Http\Requests\Conference;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DashboardMemberEventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['nullable'],
            'email' => ['nullable'],
            'country_id' => ['nullable'],

            'address_line1' => ['nullable'],
            'address_line2' => ['nullable'],

            'city' => ['nullable'],

            'state' => ['nullable'],
            'postal_code' => ['nullable'],
            'detected_country_id' => ['nullable'],
            'company_email' => ['nullable'],
            'website' => ['nullable'],
            'slogan' => ['nullable'],
            'profile' => ['nullable'],
            'business_est' => ['nullable'],

            'employees_num' => ['nullable'],


            // Contact Numbers
            'phone' => 'nullable|string',
            'phone_key_id' => 'nullable|integer',
            'fax' => 'nullable|string',
            'fax_key_id' => 'nullable|integer',

            'wsa_id' => ['nullable'],


            // conferences
            "conferences.*.conference_id" => [
                'nullable',
                Rule::exists('conferences', 'id')->where(function ($query) {
                    $query->where('deleted_at', null);
                })
            ],


        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
