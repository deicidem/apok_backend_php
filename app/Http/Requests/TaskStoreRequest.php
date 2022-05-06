<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
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
                'statusId' => 'required|integer',
                'dzzId' => 'required|integer',
                'result' => 'nullable|string',
            ];
        } else {
            return [
                'title' => 'required|string|max:258',
                'statusId' => 'required|integer',
                'dzzId' => 'required|integer',
                'result' => 'nullable|string',
            ];
        }
    }

    public function messages() {
        if (request()->isMethod('post')) {
            return [
                'title' => 'required|string|max:258',
                'statusId' => 'required|integer',
                'dzzId' => 'required|integer',
                'result' => 'nullable|string',
            ];
        } else {
            return [
                'title' => 'required|string|max:258',
                'statusId' => 'required|integer',
                'dzzId' => 'required|integer',
                'result' => 'nullable|string',
            ];
        } 
    }
}
