<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:events,code',
            'capasity' => 'required',
            'image' => 'required|file|image|mimes:jpeg,png,jpg',
            'start_date' => 'required',
            'due_date' => 'required',
        ];
    }
}
