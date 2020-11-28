<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Carbon\Carbon;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       
        if (Auth::id()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return  [
            'eventName' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:1000|min:10',
            'dateStart' => 'required|date|after_or_equal:' . Carbon::now(),
            // 'timeStart' => 'required|date|date_format:H:i',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
            // 'timeEnd' => 'required|date|date_format:H:i',
            'privacyType' => 'required',
            'tags' => 'sometimes',
            'coverImage' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'eventName.required' => 'Please give your event a name.',
            'description.required'  => 'Please give your event a description, so that attendees can understand what it is about.',
            'dateStart.after_or_equal' => 'Please pick a date in the future, or today\'s date.',
            'coverImage.required' => 'Please upload a cover image. This can be a PNG or JPG file.'
        ];
    }
}
