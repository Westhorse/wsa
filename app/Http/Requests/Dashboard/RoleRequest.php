<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $role = $this->route()->parameter('role');
            return [
                'name' => ['nullable'],
                'permissions' => "nullable|array",
                'permissions.*. '      => 'nullable|integer',
            ];
        } else {
            return [
                'name' => 'nullable',
                'permissions' => "nullable|array",
                'permissions.*.permission_id'      => 'nullable|integer',
            ];
        }
    }
}
