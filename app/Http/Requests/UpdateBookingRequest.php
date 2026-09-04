<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        return $this->user()->can('update', $booking);
    }

    public function rules(): array
    {
        return [
            'start_datetime' => ['required', 'date'],
            'end_datetime' => ['required', 'date', 'after:start_datetime'],
            'purpose' => ['required', 'string', 'max:500'],
            'participants' => ['required', 'integer', 'min:1'],
            'priority' => ['required', 'string', Rule::in(['LOW', 'NORMAL', 'HIGH', 'URGENT'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = Carbon::parse($this->start_datetime);
            $end = Carbon::parse($this->end_datetime);
            
            if ($start->format('Y-m-d') !== $end->format('Y-m-d')) {
                $validator->errors()->add('end_datetime', 'Bookings must start and end on the same day.');
            }
        });
    }
}
