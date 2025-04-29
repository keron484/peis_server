<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'start_date' => 'required|date|date_format:Y-m-d H:i',
            'end_date' => 'required|date|date_format:Y-m-d H:i|after_or_equal:start_date',
            'status' => 'sometimes|nullable|string|max:50',
            'total_tickets' => 'required|integer|min:1',
            'ticket_price' => [
                'required',
                'regex:/^-?\d+(\.\d+)?$/',
            ],
            'minimum_winnings' => [
                'required',
                'regex:/^-?\d+(\.\d+)?$/',
            ],
        ];
    }
}
