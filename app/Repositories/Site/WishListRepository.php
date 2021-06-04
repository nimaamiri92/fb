<?php


namespace App\Repositories\Site;

use App\Models\Product;
use App\Models\Site\User;
use App\Models\Slider;
use App\Models\WishList;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class WishListRepository extends BaseRepository
{
    public function __construct(WishList $wishList)
    {
        parent::__construct($wishList);
        $this->model = $wishList;
    }

    public function addToMyWishList(Product $product)
    {
        $this->model->updateOrInsert(
            [
                'user_id' => currentUserObj()->id,
                'product_id' => $product->id
            ],
            [
                'user_id' => currentUserObj()->id,
                'product_id' => $product->id
            ]
        );
    }

    public function index(User $user)
    {
        return $user->load('wishlists.product.image');
    }
}