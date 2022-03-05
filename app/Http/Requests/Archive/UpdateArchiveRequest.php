<?php

namespace App\Http\Requests\Archive;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArchiveRequest extends FormRequest
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
            'title' => 'required|string',
            'year' => 'required',
            'tome' => 'required',
            'description' => 'required|string',
            'id' => 'required|exists:archives',
            'document' => 'required|mimes:pdf'
        ];
    }
}
