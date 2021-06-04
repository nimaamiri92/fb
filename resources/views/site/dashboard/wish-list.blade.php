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
                        @if($wishList->wishlists->isNotEmpty())
                            @foreach($wishList->wishlists as $item)
                                <div class="col-6 col-md-3 my-3">
                                    <div class="product-list__item">
                                        <a href="{{ route('site.product.show', ['product' => $item->id]) }}" class="d-flex flex-column text-decoration-none">
                                            <img class="product-list__item__image"
                                                 src="{{ $item->product->image }}"
                                                 alt="">
                                            <div class="px-3 pt-3">
                                                <p class="product-list__item__title">{{ $item->product->product_name }}</p>
                                                <p class="product-list__item__price"> تومان{{ $item->product->price }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection