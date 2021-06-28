<?php

namespace App\Http\Requests\Site;

use App\Http\Requests\BaseRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\Rule;

class ContactUsRequest extends BaseRequest
{
    public function postRule()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['required', 'phone:IR'],
            'message' => ['required'],
        ];
    }
}
