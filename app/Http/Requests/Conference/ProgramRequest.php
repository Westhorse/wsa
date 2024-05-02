<?php

namespace App\Http\Requests\Conference;

use App\Models\Program;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
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
            'name' => 'required|string',
            'location' => 'nullable|string',
            'dress_code' => 'nullable|string',
            'description' => 'nullable|string',

            'from' => [
                'required',
                'date_format:H:i',
                Rule::unique('programs')->where(function ($query) {
                    return $query->where('day_id', $this->day_id);
                })->where(function ($query) {
                    return $query->where('from', '<', $this->input('to') ?? "null")
                        ->where('to', '>', $this->input('from'));
                })->ignore($this->program),
                function ($attribute, $value, $fail) {
                    $conflictingPrograms = Program::where('day_id', $this->day_id)
                        ->where(function ($query) use ($value) {
                            $query->where('from', '<', $value)
                                ->where('to', '>', $value)->where('day_id', $this->day_id);;
                        })
                        ->exists();

                    if ($conflictingPrograms) {
                        $fail('There is a program already scheduled at this time.');
                    }
                },
            ],


            'to' => [
                'nullable',
                'date_format:H:i',
                    Rule::unique('programs')->where(function ($query) {
                    return $query->where('day_id', $this->day_id);
                })->where(function ($query) {
                    return $query->where('from', '<', $this->input('to'))
                        ->where('to', '>', $this->input('from'));
                })->ignore($this->program)
            ],

            'active' => 'boolean',
            'day_id' => 'required|exists:event_days,id',
        ];
    }

    public function messages()
    {
        return [
            'to.unique' => 'There is a program already scheduled at this time',
        ];
    }
}
