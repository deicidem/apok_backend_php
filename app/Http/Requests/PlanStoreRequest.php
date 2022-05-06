<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanStoreRequest extends FormRequest
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
        if (request()->isMethod('post')) {
            return [
                'title' => 'required|string|max:258',
                'text' => 'required|string',
            ];
        } else {
            return [
                'title' => 'required|string|max:258',
                'text' => 'required|string',
            ];
        }
    }

    public function messages() {
        if (request()->isMethod('post')) {
            return [
                'title' => 'Title is required',
                'text' => 'Text is required',
            ];
        } else {
            return [
                'title' => 'Title is required',
                'text' => 'Text is required',
            ];
        } 
    }
}
