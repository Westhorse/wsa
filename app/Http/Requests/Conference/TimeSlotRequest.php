<?php

namespace App\Http\Requests\Conference;

use App\Models\TimeSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TimeSlotRequest extends FormRequest
{
   /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->getMethod() == 'PUT' || $this->getMethod() == 'PATCH') {
            $uniqueRuleTimeFrom = Rule::unique('time_slots')->where(function ($query) {
                return $query->where('day_id', $this->day_id);
            })->ignore($this->time_slot);

            $uniqueRuleTimeTo = Rule::unique('time_slots')->where(function ($query) {
                return $query->where('day_id', $this->day_id);
            })->ignore($this->time_slot);

        } else {
            $uniqueRuleTimeFrom = Rule::unique('time_slots')->where(function ($query) {
                return $query->where('day_id', $this->day_id);
            })->where(function ($query) {
                return $query->where('time_from', '<', $this->input('time_to'))
                    ->where('time_to', '>', $this->input('time_from'));
            });

            $uniqueRuleTimeTo = Rule::unique('time_slots')->where(function ($query) {
                return $query->where('day_id', $this->day_id);
            })->where(function ($query) {
                return $query->where('time_from', '<', $this->input('time_to'))
                    ->where('time_to', '>', $this->input('time_from'));
            });
        }

        $rules = [
            'time_from' => [
                'required',
                'date_format:H:i',
                $uniqueRuleTimeFrom,
                function ($attribute, $value, $fail) {
                    $conflictingPrograms = TimeSlot::where('day_id', $this->day_id)
                        ->where(function ($query) use ($value) {
                            $query->where('time_from', '<', $value)
                                ->where('time_to', '>', $value);
                        })
                        ->exists();

                    if ($conflictingPrograms) {
                        $fail('There is a Time Slot already scheduled at this time.');
                    }
                },
            ],

            'time_to' => [
                'required',
                'date_format:H:i',
                'after:time_from',
                $uniqueRuleTimeTo,
            ],

            'active' => ['boolean'],
            'default_status' => ['boolean'],
            'note' => ['nullable', 'string'],
            'day_id' => ['required', 'exists:event_days,id'],
        ];

        return $rules;
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
    public function messages()
    {
        return [
            'to.unique' => 'There is a Time Slot already scheduled at this time',
        ];
    }
}
