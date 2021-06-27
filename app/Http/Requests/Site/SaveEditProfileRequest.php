<?php

namespace App\Http\Requests\Site;

use App\Http\Requests\BaseRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Validation\Rule;

class SaveEditProfileRequest extends BaseRequest
{
    public function postRule()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . currentUserObj()->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'mobile' => ['required', 'phone:IR', 'unique:users,mobile,' . currentUserObj()->id],
        ];
    }
}
