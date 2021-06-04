<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Models\Product;
use App\Repositories\Site\WishListRepository;
use Illuminate\Support\Facades\Auth;

class WishListController extends BaseController
{

    /**
     * @var WishListRepository
     */
    private $wishListRepository;

    public function __construct(WishListRepository $wishListRepository)
    {
        $this->wishListRepository = $wishListRepository;
    }

    public function store(Product $product)
    {
        $this->wishListRepository->addToMyWishList($product);
        return redirect(route('site.user.wish-list'));
    }

    public function index()
    {
        $this->setPageTitle('علاقه مندی ها');
        $this->setCartContent();
        $user = Auth::user();
        $wishList = $this->wishListRepository->index($user);
        return view('site.user.wish-list',compact('wishList'));
    }
}