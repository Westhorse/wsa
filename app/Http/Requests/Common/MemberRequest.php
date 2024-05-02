<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
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
//            'name' => ['nullable'],
            'email' => ['nullable'],
            'address_line1' => ['nullable'],
            'address_line2' => ['nullable'],
//            'city' => ['nullable'],
//            'state' => ['nullable'],
//            'postal_code' => ['nullable'],
            'website' => ['nullable'],
            'profile' => ['nullable'],
//            'branches' => ['nullable'],
            'business_est' => ['nullable'],
            'employees_num' => ['nullable'],
//            'country_id' => ['nullable'],

            // Contact Numbers
            'phone' => 'nullable|string',
            'phone_key_id' => 'nullable|integer',
            'fax' => 'nullable|string',
            'fax_key_id' => 'nullable|integer',

            // Services
            "services.*.service_id" => [
                'nullable',
                Rule::exists('services', 'id')->where(function ($query) {
                    $query->where('deleted_at', null);
                })
            ],

            // Certificates
            "certificates.*.certificate_id" => [
                'nullable',
                Rule::exists('certificates', 'id')->where(function ($query) {
                    $query->where('deleted_at', null);
                })
            ],
        ];
    }
}
