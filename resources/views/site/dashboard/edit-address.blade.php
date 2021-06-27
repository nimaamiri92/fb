@extends('site.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="page-content">
        <div class="row pt-3 pt-md-5">
            <div class="col-12 col-md-3">
                @include('site.dashboard.profile-sidebar')
            </div>
            <div class="col-12 col-md-9">
                <div class="profile-page address-book-page">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="page-title">
                                ویراش آدرس
                            </h1>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <form class="w-100" method="post" action="{{ route('site.addresses.update',$address->id) }}">
                            @csrf
                            <div class="col-12 col-md-6">
                                <div class="form-group text-right">
                                    <label class="required" for="fullname">
                                        نام و نام خانوادگی
                                    </label>
                                    <input type="text" value="{{ $address->name_of_receiver }}"
                                           class="form-control @error('name_of_receiver') is-invalid @enderror"
                                           name="name_of_receiver" id="fullname">
                                    @error('name_of_receiver')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <label class="required" for="phonenumber">
                                        تلفن
                                    </label>
                                    <input type="text" value="{{ $address->phone }}"
                                           class="form-control @error('phone') is-invalid @enderror" name="phone"
                                           id="phonenumber">
                                    @error('phone')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group text-right">
                                    <label class="required" for="address">
                                        آدرس پستی
                                    </label>
                                    <input type="text" value="{{ $address->address }}"
                                           class="form-control @error('address') is-invalid @enderror" name="address"
                                           id="address">
                                    @error('address')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <label class="required" for="pastalcode">
                                        کد پستی
                                    </label>
                                    <input type="text" value="{{ $address->postal_code }}"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           name="postal_code" id="pastalcode">
                                    @error('postal_code')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <label class="required" for="province">
                                        استان
                                    </label>
                                    <input type="text" value="{{ $address->province }}"
                                           class="form-control @error('province') is-invalid @enderror" name="province"
                                           id="province">
                                    @error('province')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="form-group text-right">
                                    <label class="required" for="city">
                                        شهر
                                    </label>
                                    <input type="text" value="{{ $address->city }}"
                                           class="form-control @error('city') is-invalid @enderror" name="city"
                                           id="city">
                                    @error('city')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                </div>
                                <div class="form-group d-flex align-items-center">
                                    <input type="checkbox" value="1" name="is_default" @if($address->is_default) checked @endif>
                                    @error('is_default')
                                    <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                    @enderror
                                    <label for="" class="form-check-label mr-2">
                                        استفاده به عنوان آدرس پیش فرض
                                    </label>
                                </div>
                                <div class="d-flex justify-content-start flex-column flex-md-row mt-5">
                                    <button class="btn action-btn" type="submit">ذخیره آدرس</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection