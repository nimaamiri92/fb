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

        //here if admin user filter orders by order status and want to return to state that all orders to show
        if (!empty($filter['order_state'])){
            if ($filter['order_state'] === "EMPTY"){
                unset($filter['order_state']);
            }
        }
        if (!empty($filter['payment_status'])){
            if ($filter['payment_status'] === "EMPTY"){
                unset($filter['payment_status']);
            }
        }

        //it is date format,sorry for this type of coding
        //business at the sudden want something!!!!
        //here we check if admin user search in order by date
        if (!empty($filter['search'])){
            if (count(explode('/',$filter['search'])) > 2){
                $filter['search'] = convertToGregorian(explode('/',$filter['search']))->format('Y-m-d');
            }
        }

        //for understand to what happen below you need to read `app/tools/filterModel` directory
        //here we search,sort,filter on object and their relations
        $query->magicQuery(
            $filter,
            ['relation_user__name','relation_user__mobile','name_of_receiver','address','phone','id','created_at','total_order_price'],
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
