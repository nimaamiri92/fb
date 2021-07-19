<?php


namespace App\Services\Site;

use App\Jobs\RemoveStockEntityBaseOnTheOrderJob;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment as PaymentModel;
use App\Models\ProductAttribute;
use App\Models\Shipment;
use App\Repositories\Site\CartRepository;
use App\Repositories\Site\OrderRepository;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class OrderService
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(CartRepository $cartRepository, OrderRepository $orderRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
    }

    public function checkOrderHasEnoughEntity($listOfCartProducts)
    {
        foreach ($listOfCartProducts as $eachCartItem) {
            if (!$eachCartItem['has_enough_entity']) {
                return false;
            }
        }

        return true;
    }


    public function createOrderAndGetInitialPaymentData($cartData, $addressData, $shipmentData)
    {
        try {
            DB::beginTransaction();

            $order = new Order;
            $order->cart_id = $cartData['cartId'];
            $order->user_id = currentUserObj()->id;
            $order->name_of_receiver = $addressData->name_of_receiver;
            $order->province = $addressData->province;
            $order->city = $addressData->city;
            $order->address = $addressData->address;
            $order->postal_code = $addressData->postal_code;
            $order->phone = $addressData->phone;
            $order->shipment_type_name = $shipmentData->name;
            $order->shipment_type_price = $shipmentData->price;
            $order->total_order_price = $cartData['totalPriceWithDiscount'] + $shipmentData->price;
            $order->payment_status = PaymentModel::FAIL_PAYMENT;
            $order->save();

            //convert cart items to order items
            $order->orderItems()->saveMany(
                 $this->generateOrderItemsData($cartData['listOfProducts'])
            );

            //create initial payment log
            $payment = $this->createPayment($order);

            //here we clean current user cart items(only cart with active status allow to show user)
            $this->cartRepository->changeCartStatus(Cart::GO_FOR_PAYMENT);

            //before send user to payment we decrement each product quantity
            foreach ($cartData['listOfProducts'] as $eachCartItem) {
                ProductAttribute::query()->where('id', $eachCartItem['product_attribute_id'])->decrement('quantity', $eachCartItem['quantity']);
            }
            //Here we allow user to pay order in certain time(base on the bank gateway,each payment allow to be pay in only 15 minutes
            //if user go to payment and didn't come back with successful payment
            //we increase that order product quantity(return product quantity)
            RemoveStockEntityBaseOnTheOrderJob::dispatch($cartData)->delay(now()->addMinutes(17));

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
        }
        return $payment;
    }


    private function generateOrderItemsData($listOfProducts): array
    {
        $orderItemsData = [];
        foreach ($listOfProducts as $key => $eachCartItem) {
            $orderItem = new OrderItem();
            $orderItem->quantity = $eachCartItem['quantity'];
            $orderItem->product_id = $eachCartItem['product_id'];
            $orderItem->product_image = $eachCartItem['image'];
            $orderItem->quantity = $eachCartItem['quantity'];
            $orderItem->product_price = $eachCartItem['product_price_discount'];
            $orderItem->product_size = $eachCartItem['product_size'];
            $orderItem->product_name = $eachCartItem['product_name'];
            array_push($orderItemsData, $orderItem);
        }
        return $orderItemsData;
    }

    private function createPayment(Order $order)
    {
        $defaultGateway = getDefaultBankGateway();
        $invoice = new Invoice();
        $invoice->amount($order->total_order_price);

        $payment = Payment::via($defaultGateway)->purchase($invoice, function ($driver, $transactionId) use ($order) {
            $order->payments()->create([
                'total_order_price' => $driver->getInvoice()->getAmount(),
                'order_reference' => $transactionId,
            ]);
        });

        return $payment;
    }
}
