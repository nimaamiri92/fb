@extends('site.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="page-content">
        <div class="row pt-5">
            <div class="col-12 col-md-3">
                @include('site.dashboard.profile-sidebar')
            </div>
            <div class="col-12 col-md-9">
                <div class="profile-page">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="page-title">
                                تاریخچه سفارشات
                            </h1>
                        </div>
                    </div>
                    @if(!$orderHistory->orders->isEmpty())
                        @foreach($orderHistory->orders as $order)
                            <div class="row border-bottom mb-4">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6 col-sm-3 d-flex">
                                            <label class="ml-3" for="">مبلغ کل:</label>
                                            <p>{{ $order->total_order_price }} تومان</p>
                                        </div>
                                        <div class="col-6 col-sm-3 d-flex justify-content-center">
                                            <p>{{ $order->order_date }}</p>
                                        </div>
                                        <div class="col-6 col-sm-3 d-flex">
                                            <label class="ml-3" for="">شماره سفارش:</label>
                                            <p>{{ $order->id }}</p>
                                        </div>
                                        <div class="col-6 col-sm-3 d-flex justify-content-center justify-content-sm-end">
                                            <a href="{{ route('site.dashboard.order-history-details',['order' => $order->id]) }}">مشاهده سفارش</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 order-history-images-container d-flex py-4">
                                            <div class="overflow-auto d-flex">
                                                @foreach($order->orderItems as $orderItem)
                                                    <a class="order-history-image"
                                                       href="{{ route('site.product.show', ['product' => $orderItem->product_id]) }}">
                                                        <img
                                                                src="{{ $orderItem->product_image }}"
                                                                alt="">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
