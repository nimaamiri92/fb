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
                            ویرایش اطلاعات حساب کاربری
                        </h1>
                    </div>
                </div>
                <form class="row edit-info-form" method="post" action="{{ route('site.dashboard.save-edit-profile') }}">
                    @csrf
                    <fieldset class="col-12 col-md-6 mb-5">
                        <div class="form-row">
                            <h2 class="mb-5">
                                اطلاعات حساب کاربری
                            </h2>
                        </div>
                        <div class="form-group text-right">
                            <label for="firstName" class="required">
                                نام و نام خانوادگی
                            </label>
                            <input name="name" type="text" id="firstName" value="{{ $user->name }}" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <label for="telephone">
                                تلفن
                            </label>
                            <input name="mobile" type="text" id="telephone" value="{{ $user->mobile }}" class="form-control @error('mobile') is-invalid @enderror">
                            @error('mobile')
                            <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <label for="email" class="required">
                                ایمیل
                            </label>
                            <input name="email"  type="email" id="email" value="{{ $user->email }}" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                            <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </fieldset>
                    <fieldset class="col-12 col-md-6 mb-5">
                        <div class="form-row">
                            <h2 class="mb-5">
                                تغییر ایمیل و رمز عبور
                            </h2>
                        </div>
                        <div class="form-group text-right">
                            <label for="newPassword" class="required">
                                رمز عبور جدید
                            </label>
                            <input name="password" type="password" id="newPassword" class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                            <span class="validation-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <label for="newPasswordRepeat" class="required">
                                تکرار رمز عبور جدید
                            </label>
                            <input name="password_confirmation" type="password" id="newPasswordRepeat" class="form-control">
                        </div>
                    </fieldset>
                    <div class="col-12 form-row">
                        <button type="submit" class="btn action-btn">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection