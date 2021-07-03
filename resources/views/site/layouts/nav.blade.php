<nav class="border-bottom bg-white">
    <div class="container main-content">
        <div class="navbar header-container align-items-stretch p-0">
            <button class="btn btn-light d-block d-lg-none" id="openNav">
                <i class="fas fa-bars"></i>
            </button>
            <div class="flex-grow-0 navbar__logo-wrapper">
                <a href="/">
                    <img class="navbar__logo" src="{{ asset('site/images/bcc-logo.png') }}" alt="bcc">
                </a>
            </div>
            <div id="mobileNav" class="align-items-stretch flex-grow-1 overlay bg-white d-lg-flex">
                @foreach($menus as $menu)
                    @if(!$menu->parent_id)
                        <div class="overlay-content mega-menu d-flex align-items-center navbar__item flex-1 pointer">
                            <div class="w-100 text-center">
                                <span class="d-flex d-lg-inline-block justify-content-between">
                                     <a class="navbar__item__text">
                                         {{ $menu->name }}
                                     </a>
                                    @if($menu->children->count() > 0)
                                        <button class="btn d-inline-block navbar__item__btn d-lg-none">
                                            <i class="fas fa-angle-down"></i>
                                        </button>
                                    @endif
                                </span>
                                <div class="mega-menu__content">
                                    <div class="d-flex">
                                        <div class="row flex-grow-1">
                                            @foreach($menu->children as $middleChildMenu)
                                                @if($middleChildMenu->status)
                                                    <div class="col-lg col-12 pt-3 pt-lg-0 d-flex flex-column">
                                                        <div
                                                                class="d-flex d-lg-inline-block justify-content-between align-items-center mega-menu__title text-md-right">
                                                            <a class="" href="{{ $middleChildMenu->link }}">
                                                                {{ $middleChildMenu->name }}
                                                            </a>
                                                            @if($middleChildMenu->children->count() > 0)
                                                                <button
                                                                        class="btn d-inline-block d-lg-none navbar__item__btn collapsed"
                                                                        data-target="#menu-{{ $middleChildMenu->slug }}"
                                                                        data-toggle="collapse">
                                                                    <i class="fas fa-angle-up"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <ul class="list-unstyled collapse"
                                                            id="menu-{{ $middleChildMenu->slug }}">
                                                            @foreach($middleChildMenu->children as $grandChildMenu)
                                                                @if($grandChildMenu->status)
                                                                    <li>
                                                                        <a class="mega-menu__subtitle"
                                                                           href="{{ $grandChildMenu->link }}">
                                                                            {{ $grandChildMenu->name }}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        @if($menu->image)
                                            <div class="d-none d-lg-flex mr-lg-5">
                                                <img class="mega-menu__image" src="{{ $menu->image}}"
                                                     alt="">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="d-flex navbar__search-wrapper align-items-center">
                <form class="flex-1 search-form" id="searchForm" action="">
                    <div class="navbar__search d-flex align-items-center" id="dropdownMenuButton"
                         aria-haspopup="true" aria-expanded="false">
                        <i class="icon fas fa-search ml-2"></i>
                        <input class="flex-grow-1" placeholder="جستجو..." id="siteSearch" name="search" type="text">
                        <div class="dropdown-menu search-suggestions">
                            <ul id="searchResponse">

                            </ul>
                        </div>
                    </div>
                </form>
                <a class="btn wish-list-btn d-none d-md-inline-block" href="{{ route('site.dashboard.wishlist') }}">
                    <i class="far fa-heart"></i>
                </a>
                <button class="btn d-inline-block d-md-none search-btn" id="openMobileSearch" type="button">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('site.cart.show') }}" class="shopping-cart" id="shoppingCartBtn">
                    <i class="svg-icon svg-icon-cart"></i>
                    <span class="cart-counter">{{ !empty($cartItemCount) ? $cartItemCount : 0 }}</span>
                </a>
            </div>
        </div>
    </div>
</nav>


@push('scripts')
    <script>

        /*
        * Its your fault, you dont pay the project money than I will use mysql for your search instead of Elasticsearch!!!
        * */
        $(document).ready(function () {
            $("#siteSearch").keyup(function () {
                var keyword = this.value;

                if (keyword.length === 0){
                    $('.search-suggestions').removeClass('show');
                }


                if (keyword.length > 2) {
                    $.get({
                        url: "/api/search?search=" + keyword,
                        beforeSend: function () {
                            $("#loader").addClass('loading');
                        },
                        success: function (data) {
                            $('.search-suggestions').addClass('show');
                            var  output = '';

                            $.each( data.result, function( key, value ) {
                                var li = '<li class="d-flex border-bottom p-3">' +
                                    '<img class="ml-3" src="$product_image" alt="">' +
                                    '<div class="d-flex flex-column justify-content-between">' +
                                    '<a class="search-suggestion" href="$url">' +
                                    '<span class="suggestion-title">$product_title</span>' +
                                    '</a>' +
                                    '</div>' +
                                    '</li>';
                                console.log(value.image.path)
                                li = li.replace("$product_image", value.image.path);
                                li = li.replace("$product_title", value.product_name);
                                li = li.replace("$url", 'product/' + value.id);
                                output += li
                            });


                                $('#searchResponse').html(output);
                                $("#loader").removeClass('loading');
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            // $('#add_to_cart').css("background-color", "red");
                            // $('#add_to_cart').text("خطایی رخ داده!!!");
                            // $('#add_to_cart').prop('disabled', true);

                            $("#loader").removeClass('loading');
                        }
                    });
                }

            });
        });
    </script>
@endpush
