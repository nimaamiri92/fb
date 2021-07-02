<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\BranchRequest;
use App\Http\Requests\Admin\Users\UpdateAddressRequest;
use App\Http\Requests\Site\ContactUsRequest;
use App\Models\Address;
use App\Models\Branch;
use App\Models\ContactUs;
use App\Models\Site\User;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Site\AddressRepository;
use App\Repositories\Site\CityRepository;
use App\Repositories\Site\ProvinceRepository;
use Illuminate\Support\Facades\DB;

class BranchControllers extends BaseController
{
    public function index()
    {
        $this->setPageTitle('شعب');
        $this->setCartContent();
        $data = Branch::query()->get();
        return view('site.layouts.branches',compact('data'));
    }
}
