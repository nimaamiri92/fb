<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\BranchRequest;
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
    public function create()
    {
        $this->setPageTitle('تماس با ما');
        $this->setCartContent();
        return view('site.layouts.contact-us');
    }
    public function store(ContactUsRequest $request)
    {
        $this->setPageTitle('تماس با ما');
        $this->setCartContent();
        $data = $request->validated();
        ContactUs::query()->create($data);
        return redirect()->route('site.contact-us.create')->with('message', 'پیام شما با موفقیت ثبت شد.');
    }
}
