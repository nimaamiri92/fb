<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\AddressRequest;
use App\Http\Requests\Admin\Users\UpdateAddressRequest;
use App\Http\Requests\Site\ContactUsRequest;
use App\Models\Address;
use App\Models\ContactUs;
use App\Models\Site\User;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Site\AddressRepository;
use App\Repositories\Site\CityRepository;
use App\Repositories\Site\ProvinceRepository;
use Illuminate\Support\Facades\DB;

class ContactUsController extends BaseController
{
    public function __construct()
    {

    }

    public function create()
    {
        //show form
    }
    public function store(ContactUsRequest $request)
    {
        $data = $request->validated();
        ContactUs::query()->create($data);
    }
}
