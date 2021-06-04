<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Repositories\Site\UserRepository;

class DashboardController extends BaseController
{
    public  $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->setPageTitle('داشبورد');
        $this->setSideBar('dashboard');
        $dashboard = $this->userRepository->dashboard(currentUserObj());
        return view('site.dashboard.home',compact('dashboard'));
    }
}
