<?php


namespace App\Repositories\Admin;

use App\Models\Order;
use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    public function listOrders($filter)
    {
        $query = $this
            ->model
            ->newQuery()
            ->with('user');

        //it is date format,sorry for this type of coding
        //business at the sudden what something!!!!
        if (!empty($filter['search'])){
            if (count(explode('/',$filter['search'])) > 2){
                $filter['search'] = convertToGregorian(explode('/',$filter['search']))->format('Y-m-d');
            }
        }

        $query->magicQuery(
            $filter,
            ['relation_user__name','relation_user__mobile','name_of_receiver','address','phone','id','created_at'],
            ['created_at', 'id','order_state','payment_status'],
            ['order_state','payment_status']
        );

        return $query->paginate();
    }

    public function showOrder($order)
    {
        return $this->model->newQuery()
            ->where('id',$order->id)
            ->with(['payments' => function($query){
                return $query->orderBy('id','desc');
            }])
            ->with('orderItems')
            ->with('user')
            ->with('user.addresses')
            ->first();
    }
}
