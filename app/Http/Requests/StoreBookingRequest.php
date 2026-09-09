<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Booking::class);
    }

    public function rules(): array
    {
        return [
            'resource_id' => ['required', 'exists:resources,id'],
            'start_datetime' => ['required', 'date', 'after_or_equal:now'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'purpose' => ['required', 'string', 'max:500'],
            'participants' => ['required', 'integer', 'min:1'],
            'priority' => ['required', 'string', Rule::in(['LOW', 'NORMAL', 'HIGH', 'URGENT'])],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_recurring' => ['nullable', 'boolean'],
            'recurring_pattern' => ['nullable', 'string', Rule::in(['DAILY', 'WEEKLY', 'WEEKDAYS'])],
            'recurring_occurrences' => ['nullable', 'integer', 'min:2', 'max:52'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = Carbon::parse($this->start_datetime);
            $end = Carbon::parse($this->end_datetime);
            
            // Basic sanity check on dates to ensure we don't have multi-day bookings for now 
            // unless the resource specifically allows it, but for simplicity let's enforce same-day.
            if ($start->format('Y-m-d') !== $end->format('Y-m-d')) {
                $validator->errors()->add('end_datetime', 'Bookings must start and end on the same day.');
            }
        });
    }
}
