@extends('site.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
{{--    @dd($orderHistoryDetails->orders->first())--}}
    <div class="page-content">
        <div class="row pt-5">
            <div class="col-12 col-md-3">
                @include('site.dashboard.profile-sidebar')
            </div>
            <div class="col-12 col-md-9">
                <div class="profile-page shopping-history-table">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="page-title">
                                جزئیات سفارش
                            </h1>
                        </div>
                    </div>
                    <div class="row border-bottom mb-2">
                        <div class="col-md-3 col-6 d-flex">
                            <label class="ml-1" for="">تحویل گیرنده: </label>
                            <p>{{ $orderHistoryDetails->orders->first()->name_of_receiver }}</p>
                        </div>
                        <div class="col-md-3 col-6 d-flex">
                            <label class="ml-1" for="">شماره تماس: </label>
                            <p>{{ $orderHistoryDetails->orders->first()->phone }}</p>
                        </div>
                        <div class="col-6 d-flex justify-content-md-end">
                            <label class="ml-1" for="">تاریخ: </label>
                            <p>{{ $orderHistoryDetails->orders->first()->order_date }}</p>
                        </div>
                        <div class="col-12 d-flex">
                            <label class="ml-1" for="">آدرس: </label>
                            <p>{{ $orderHistoryDetails->orders->first()->address }}</p>
                        </div>
                    </div>
                    <div class="row border-bottom mb-5">
                        <div class="col-md-3 col-6 d-flex">
                            <label class="ml-1" for="">مبلغ کل:</label>
                            <p>{{  $orderHistoryDetails->orders->first()->total_order_price  }} تومان</p>
                        </div>
{{--                        <div class="col-md-3 col-6 d-flex">--}}
{{--                            <label class="ml-1" for="">تخفیف:</label>--}}
{{--                            <p>10000 تومان</p>--}}
{{--                        </div>--}}
                    </div>
                    @foreach($orderHistoryDetails->orders->first()->orderItems as $orderItem)
                        <div class="row border-bottom pb-4 mb-4 shopping-history-item-wrapper">
                            <div class="col-12 col-md-6 d-flex">
                                <a class="shopping-history__image d-flex" href="{{ route('site.product.show', ['product' => $orderItem->product_id]) }}">
                                    <img
                                            src="{{ $orderItem->product_image }}"
                                            alt="">
                                </a>
                                <div class="d-flex flex-column flex-grow-1">
                                    <a class="shopping-history__title" href="{{ route('site.product.show', ['product' => $orderItem->product_id]) }}">{{ $orderItem->product_name }}</a>
                                    <span class="d-flex align-items-baseline mt-2">
                                    <b class="ml-2">سایز:</b>
                                    {{ $orderItem->product_size }}
                                </span>
                                    <span class="d-flex align-items-baseline mt-2">
                                                                    <b class="ml-2">تعداد:</b>
                                                                    {{ $orderItem->quantity }}
                                                                </span>
                                    <span class="d-flex align-items-baseline mt-2">
                                                                    <b class="ml-2">قیمت:</b>
                                                                    {{ $orderItem->product_price }}
                                                                </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
