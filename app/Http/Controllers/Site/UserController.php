<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Order;
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

    public function index()
    {
        $this->setPageTitle('داشبورد');
        $dashboard = $this->userRepository->dashboard(currentUserObj());
        return view('site.dashboard.home', compact('dashboard'));
    }


    public function orderHistory()
    {
        $this->setPageTitle('تاریخچه سفارشات');
        $orderHistory = $this->userRepository->userOrderHistory(currentUserObj());
        return view('site.dashboard.order-history', compact('orderHistory'));
    }

    public function orderHistoryDetails(Order $order)
    {
        $this->setPageTitle('جزیات تاریخچه سفارشات');
        $orderHistoryDetails = $this->userRepository->userOrderHistoryDetails(currentUserObj(), $order);
        return view('site.dashboard.order-history-details', compact('orderHistoryDetails'));
    }
}
