@extends('site.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <h1 class="page-title">
                    آدرس شعب
                </h1>
            </div>
            <div class="col-12">
                <div class="row flex-row-reverse flex-md-row">
                    <div class="col-12 col-md-4">
                        <div class="scroll-list px-4 overflow-auto" style="max-height: 620px">
                            @foreach($data as $branch)
                                <div class="store-branch pointer px-4 d-flex flex-column justify-content-center">
                                    <h4 class="store-branch__name active">
                                        {{ $branch->name}}
                                    </h4>
                                    <p class="store-branch__address">
                                        {{ $branch->address}}
                                    </p>
                                    <a href="tel:{{ $branch->phone}}" class="store-branch__tel">
                                        تلفن:
                                        {{ $branch->phone}}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div id="storeMap" style="height: 620px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(() => {
            const branchesLocation = [
                    @foreach($data as $branch)
                        [{{ $branch->lat }} , {{ $branch->long }}],
                    @endforeach
            ];
            let store = new L.Map('storeMap', {
                key: 'web.UMNI96LA8ZJNbENYtcMWySXUkLpfSGOgqn0Bhu2K',
                maptype: 'standard-day',
                poi: true,
                center: [35.75777, 51.44251],
                zoom: 14
            });
            branchesLocation.forEach(item => {
                L.marker(new L.LatLng(...item)).addTo(store);
            })

            $('.store-branch').on('click', (event) => {
                $('.store-branch').each((i, e) => {
                    if ($(e).hasClass('active')) {
                        $(e).removeClass('active');
                    }
                })
                $(event.currentTarget).addClass('active');
                let index = $('.store-branch').index($(event.currentTarget));
                store.flyTo(new L.LatLng(...branchesLocation[index]));
            })
        });
    </script>
@endpush