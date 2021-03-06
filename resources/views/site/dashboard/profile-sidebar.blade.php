<div id="dashboardMenuBtn" class="border-bottom d-md-none w-100 d-flex justify-content-between align-items-center px-2">
    <p>مدیریت حساب</p>
    <i class="fas fa-angle-down"></i>
</div>
<div id="dashboardMenu" class="profile-sidebar p-4 d-none d-md-block md-border">
    <ul class="p-0 list-unstyled">
        <li class="text-right mb-3">
            <a class="profile-links" href="{{ route('site.dashboard.home') }}">پروفایل کاربری</a>
        </li>
        <li class="text-right mb-3">
            <a class="profile-links" href="{{ route('site.dashboard.order-history') }}">سفارش های من</a>
        </li>
        <li class="text-right mb-3">
            <a class="profile-links" href="{{ route('site.dashboard.wishlist') }}">لیست علاقه مندی ها</a>
        </li>
        <li class="text-right mb-3">
            <a class="profile-links" href="{{ route('site.addresses.index') }}">آدرس های پستی</a>
        </li>
    </ul>
</div>