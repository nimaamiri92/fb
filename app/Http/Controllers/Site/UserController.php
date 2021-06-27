<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\SaveEditProfileRequest;
use App\Models\Order;
use App\Models\Site\User;
use App\Repositories\Site\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $this->setCartContent();
        $dashboard = $this->userRepository->dashboard(currentUserObj());
        return view('site.dashboard.home', compact('dashboard'));
    }


    public function orderHistory()
    {
        $this->setPageTitle('تاریخچه سفارشات');
        $this->setCartContent();
        $orderHistory = $this->userRepository->userOrderHistory(currentUserObj());
        return view('site.dashboard.order-history', compact('orderHistory'));
    }

    public function orderHistoryDetails(Order $order)
    {
        $this->setPageTitle('جزیات تاریخچه سفارشات');
        $this->setCartContent();
        $orderHistoryDetails = $this->userRepository->userOrderHistoryDetails(currentUserObj(), $order);
        return view('site.dashboard.order-history-details', compact('orderHistoryDetails'));
    }


    public function editProfile()
    {
        $this->setPageTitle('ویرایش اطلاعات');
        $this->setCartContent();
        $user = currentUserObj();
        return view('site.dashboard.edit-personal-info', compact('user'));
    }
    public function saveEditProfile(SaveEditProfileRequest $request)
    {
        $data = $request->validated();
        $this->setPageTitle('ویرایش اطلاعات');
        $this->setCartContent();

        if (!empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        User::query()->where('id',currentUserObj()->id)->update(array_filter($data));
        return redirect()->route('site.dashboard.home');
    }
}
