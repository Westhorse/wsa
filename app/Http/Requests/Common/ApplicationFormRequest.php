<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApplicationFormRequest extends FormRequest
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
            'name' => ['nullable'],
            'email' => ['nullable'],
            'address_line1' => ['nullable'],
            'address_line2' => ['nullable'],
            'map_long' => ['nullable'],
            'map_lat' => ['nullable'],
            'slogan' => ['nullable'],
            'city' => ['nullable'],
            'state' => ['nullable'],
            'postal_code' => ['nullable'],
            'detected_country_id' => ['nullable'],
            'company_email' => ['nullable'],
            'website' => ['nullable'],
            'profile' => ['nullable'],
            'branches' => ['nullable'],
            'business_est' => ['nullable'],
            'employees_num' => ['nullable'],
            'ref_value' => ['nullable'],
            'other_certificates' => ['nullable'],
            'other_services' => ['nullable'],
            'type_company' => ['nullable'],
            'tos_acceptance' => ['nullable'],
            'referral_id' => ['nullable'],
            'country_id' => ['nullable'],

            // Contact Numbers
            'phone' => 'nullable|string',
            'phone_key_id' => 'nullable|integer',
            'fax' => 'nullable|string',
            'fax_key_id' => 'nullable|integer',

            // Contact People
            'contactPersons.*.title' => ['nullable'],
            'contactPersons.*.name' => ['nullable'],
            'contactPersons.*.job_title' => ['nullable'],
            'contactPersons.*.email' => ['nullable'],
            'contactPersons.*.birth_date' => ['nullable'],
            'contactPersons.*.phone_key_id' => ['nullable'],
            'contactPersons.*.cell_key_id' => ['nullable'],
            'contactPersons.*.phone.selected' => 'nullable|integer',
            'contactPersons.*.phone.value' => 'nullable|string',
            'contactPersons.*.cell.selected' => 'nullable|integer',
            'contactPersons.*.cell.value' => 'nullable|string',

            // Trade References
            'tradeReferences.*.name' => ['nullable'],
            'tradeReferences.*.email' => ['nullable'],
            'tradeReferences.*.person' => ['nullable'],
            'tradeReferences.*.job_title' => ['nullable'],
            'tradeReferences.*.city' => ['nullable'],
            'tradeReferences.*.country_id' => [
                'nullable',
                Rule::exists('countries', 'id')->where(function ($query) {
                    $query->where('deleted_at', null);
                }),

            ],

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
