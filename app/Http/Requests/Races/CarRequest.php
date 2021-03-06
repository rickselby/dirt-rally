<?php

namespace App\Http\Requests\Races;

use App\Http\Requests\Request;

class CarRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ac_identifier' => 'required|string',
            'name' => 'required|string',
            'short_name' => 'required|string',
        ];
    }
}
