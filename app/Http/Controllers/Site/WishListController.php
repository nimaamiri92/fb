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
        return redirect(route('site.dashboard.wishlist'));
    }

    public function index()
    {
        $this->setPageTitle('علاقه مندی ها');
        $this->setCartContent();
        $wishList = $this->wishListRepository->index(currentUserObj());
        return view('site.dashboard.wish-list',compact('wishList'));
    }
}