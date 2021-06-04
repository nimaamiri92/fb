<?php


namespace App\Repositories\Site;

use App\Models\Address;
use App\Models\Order;
use App\Models\Site\User;
use App\Repositories\BaseRepository;
use App\Traits\UploadableTrait;

class UserRepository extends BaseRepository
{
    use  UploadableTrait;


    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->model = $user;
    }

    public function listUsers($paginate = false, $searchPhrase = null, string $order = 'id', string $sort = 'desc',
                              array $columns = ['*'])
    {
        if ($paginate) {
            $data = $this->paginate($columns, $order, $sort, $searchPhrase);
        } else {
            $data = $this->all($columns, $order, $sort);
        }

        return $data;
    }

    public function saveUserAddress($user, $addressData)
    {
        $user->addresses()->save(new Address($addressData));
    }

    public function dashboard(User $user)
    {
        return $user->load(['addresses' => function ($query) {
            $query->where('is_default', 1);
        }]);
    }

    public function userOrderHistory(User $user)
    {
        return $user->load(['orders', 'orders.orderItems']);
    }


    public function userOrderHistoryDetails(User $user, Order $order)
    {
        return $user->load(['orders' => function ($query) use ($order,$user){
            $query->where('user_id', $user->id);
            $query->where('id', $order->id);
        }, 'orders.orderItems']);
    }
}