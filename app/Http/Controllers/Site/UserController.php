<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\Site\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function dashboard()
    {
        $this->setPageTitle('داشبورد');
        $this->setCartContent();
        $user = Auth::user();
        $dashboardData = $this->userRepository->dashboard($user);
        return view('site.user.dashboard',compact('dashboardData'));
    }
}
