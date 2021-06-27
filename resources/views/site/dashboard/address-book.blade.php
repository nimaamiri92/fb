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
                                آدرس های پستی
                            </h1>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-12">
                            <p class="profile-section-title">
                                آدرس پیش فرض
                            </p>
                        </div>
                        @if(!empty($defaultAddress))
                            <div class="col-12">
                            <p>
                                نام گیرنده:
                                {{ $defaultAddress->name_of_receiver }}
                            </p>
                            <p>
                                {{ $defaultAddress->province }} , {{ $defaultAddress->city }}, {{ $defaultAddress->address }}
                            </p>
                            <p>
                                کدپستی:
                                {{ $defaultAddress->postal_code }}
                            </p>
                            <p>
                                تلفن:
                                {{ $defaultAddress->phone }}
                            </p>
                            <div class="d-flex">
                                <a class="profile-section-actions text-right" href="{{ route('site.addresses.edit',$defaultAddress->id) }}">
                                    ویرایش آدرس
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="profile-section-title">
                                سایر آدرس ها
                            </p>
                        </div>
                        @foreach($otherAddresses as $otherAddress)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex flex-column align-items-start mb-3">
                                <address class="flex-grow-1">
                                    <p>
                                        نام گیرنده:
                                        {{ $otherAddress->name_of_receiver }}
                                    </p>
                                    <p>
                                        {{ $otherAddress->province }} , {{ $otherAddress->city }}, {{ $otherAddress->address }}
                                    </p>
                                    <p>
                                        کدپستی:
                                        {{ $otherAddress->postal_code }}
                                    </p>
                                    <p>
                                        تلفن:
                                        {{ $otherAddress->phone }}
                                    </p>
                                </address>
                                <div class="d-flex">
                                    <a class="profile-section-actions pl-4 border-left ml-4" href="{{ route('site.addresses.edit',$otherAddress->id) }}">
                                        ویرایش آدرس
                                    </a>
                                    <a class="profile-section-actions" href="{{ route('site.addresses.delete',$otherAddress->id) }}">حذف آدرس</a>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="d-flex flex-column flex-md-row margin-top-4">
                        <a href="{{ route('site.addresses.create') }}" class="btn action-btn">
                            افزودن آدرس جدید
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection