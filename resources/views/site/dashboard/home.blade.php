@extends('site.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
<div class="page-content">
    <div class="row pt-5">
        <div class="col-12 col-md-3">
            @include('site.ZAHRA.profile-sidebar')
        </div>
        <div class="col-12 col-md-9">
            <div class="profile-page">
                <div class="row">
                    <div class="col-12">
                        <h1 class="page-title">
                            پروفایل کاربری
                        </h1>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-12">
                        <p class="profile-section-title">
                            اطلاعات حساب کاربری
                        </p>
                    </div>
                    <div class="col-12">
                        <p class="profile-section-subtitle">
                            اطلاعات شخصی
                        </p>
                        <p>
                           {{ $dashboard->name }}
                        </p>
                        <p>
                            {{ $dashboard->email ?? '-' }}
                        </p>
                        <p>
                            {{ $dashboard->mobile }}
                        </p>
                        <div class="d-flex">
                            <a class="profile-section-actions pl-4 border-left ml-4" href="">
                                ویرایش
                            </a>
                            <a class="profile-section-actions" href="">تغییر رمز عبور</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <p class="profile-section-title">
                            آدرس ها
                            <a href="">مدریت آدرس ها</a>
                        </p>
                    </div>
                    <div class="col-12 d-flex flex-column align-items-start">
                        <strong class="profile-section-subtitle text-right">
                            آدرس پستی پیش فرض
                        </strong>

                        @if($dashboard->addresses->isEmpty())
                            <p>
                                شما آدرس پیش فرض ثبت نکرده اید.
                            </p>
                        @else
                            <p>
                                {{ $dashboard->addresses->first()->address }}
                            </p>
                            <a class="profile-section-actions" href="">
                                ویرایش آدرس
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection