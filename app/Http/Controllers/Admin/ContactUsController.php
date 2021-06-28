<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ContactUs;
use App\Models\Site\User;
use App\Repositories\Admin\UserRepository;
use Illuminate\Http\Request;

class ContactUsController extends BaseController
{
    public function index()
    {
        $this->setPageTitle('مدیریت تماس با ما');
        $this->setSideBar('contact-us');
        $data = ContactUs::query()->paginate(30);
        return view('admin.contactUs.index', compact('data'));
    }
}
