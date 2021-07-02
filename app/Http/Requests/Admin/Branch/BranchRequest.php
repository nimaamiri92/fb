<?php

namespace App\Http\Requests\Admin\Branch;

use App\Http\Requests\BaseRequest;

class BranchRequest extends BaseRequest
{
    public function postRule()
    {
        return [
            'name' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'address' => [
                'required',
            ],
            'lat' => ['required'],
            'long' =>[
                'required',
            ],
        ];
    }
}
