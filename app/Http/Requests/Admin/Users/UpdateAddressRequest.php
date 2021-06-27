<?php

namespace App\Http\Requests\Admin\Users;

use App\Http\Requests\BaseRequest;
use Baloot\Models\City;
use Baloot\Models\Province;
use Illuminate\Validation\Rule;

class UpdateAddressRequest extends BaseRequest
{
    public function postRule()
    {
        return [
            'province' => [
                'required'
            ],
            'city' => [
                'required'
            ],
            'postal_code' => [
                'required',
                'integer',
                'digits:10'
            ],
            'name_of_receiver' => ['required'],
            'phone' =>[
                'required',
                'phone:IR',
                'unique:admins,mobile'
            ],
            'address' => ['required'],
            'is_default' => ['nullable']
        ];
    }
}
