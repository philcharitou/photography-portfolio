<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Properties -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title></title>
    <meta property="og:title" content="Phil Charitou">

    <meta name="description" content="An internal system for customers of Charitou Multimedia Solutions">
    <meta property="og:description" content="An internal system for customers of Charitou Multimedia Solutions">

    <meta property="og:url" content="https://www.philcharitou.com/">
    <meta name="language" content="en">
    <meta name="author" content="Phil Charitou">
    <!-- End of Meta Properties -->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">

    <!--<link rel="stylesheet" href="https://phil-charitou-static-files.b-cdn.net/css/site.css" />-->
    <link rel="stylesheet" href="/css/site.css" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/a3e44f489f.js" crossorigin="anonymous"></script>

    <!-- Font Connections -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Karla:wght@200;300;400;500;600;700;800&family=Pacifico&family=Yanone+Kaffeesatz:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500&family=Roboto+Slab:wght@100;200;300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">

    <script defer src='https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js'></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <script defer src="https://unpkg.com/alpinejs"></script>
</head>

<body id="page-top" class="min-h-screen">
    <!-- Navigation Bar -->
    <nav class="flex w-full justify-between items-center bg-white px-6 py-3 sticky top-0 left-0">
        <div class="flex">
            <!-- Nav Bar Logo -->
            <ul id="companyLogo" class="">
                <li class="nav-item dropdown">
                    <a id="companyDropdown" class="nav-link dropdown-toggle float-start" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('/assets/pch.svg') }}" class="h-8 no-lightbox"
                             alt="Logo">
                    </a>
                </li>
            </ul>
        </div>

        <!-- Nav Bar Content -->
        <div class="flex navigation-submenu">
            <!-- Right Side Of Navbar -->
            <ul id="navbar-right" class="flex justify-end items-center gap-5">
                <!-- Nav Item, Search Bar -->
                <li id="header-search" class="">
                    <form method="POST" action="{{ route('search_bar') }}">
                        @csrf

                        <div class="flex items-center text-sm">
                            <div class="bg-neutral-200 border-light rounded-l-md p-2">
                                <span class=""><i class="fas fa-search"></i></span>
                            </div>
                            <input id="query" class="text-sm border-light" name="search_query" type="search" autocomplete="off" placeholder="{{ __('trans.search') }}" class="form-control" required>
                            <input type="submit" style="position: absolute; height: 0; width: 0; border: none; padding: 0;" hidefocus="true" tabindex="-1"/>
                            <div class="bg-neutral-200 border-light rounded-r-md p-2">
                                <button class="input-group-text">{{ __('trans.search') }}</button>
                            </div>
                        </div>
                    </form>
                </li>

                <!-- Nav Item, Region Select -->
                <li class="nav-item dropdown">
                    <a id="languageDropdown" class="nav-link dropdown-toggle float-start" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @switch(LaravelLocalization::getCurrentLocale())
                            @case('zh')
                                <img src="{{asset('img/flag_china.png')}}" class="no-lightbox" style="height: 20px" alt="Chinese Flag">
                                @break
                            @default
                                <img src="{{asset('img/flag_us.webp')}}" class="no-lightbox" style="height: 20px" alt="US Flag">
                        @endswitch
                    </a>
                    <div id="region-select" class="hidden">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <a class="dropdown-item" hreflang="{{ $localeCode }}"
                               href="{{ LaravelLocalization::getLocalizedURL("$localeCode", null, [], true) }}">{{ $properties['native'] }}</a>
                        @endforeach
                    </div>
                </li>

                <!-- Nav Item, Alerts -->
                <li class="flex">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span id="notification_count" class="bg-red-700 text-white px-2 py-1 rounded-full {{ 1 ? "" : "hidden" }}">
                            1
                        </span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div id="notifications" class="hidden"
                         aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            {{ __('pages.notifications') }}
                        </h6>

                        <div id="notification">
                            @if(1 > 0)
                                @php($notifications = [])
                                @foreach($notifications as $key => $notification)
                                    @if($key < 10)
                                        <div class="notification dropdown-item d-flex align-items-center mb-1">
                                            <div class="pointer flex float-start overflow-hidden me-4" onclick="markNotificationRead('{{ $notification->id }}', '{{ $notification->data['link'] }}')">
                                                <div class="float-start me-3">
                                                    <div class="icon-circle">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                </div>
                                                <div class="float-start me-4">
                                                    <div class="small text-gray-500">
                                                        {{ $notification->created_at->toDateString() }}
                                                    </div>
                                                    {{ $notification->data['subject'] }} {{ $notification->data['title'] }} {{ $notification->data['prompt'] }}
                                                </div>
                                            </div>
                                            <div id="notification_{{ $notification->id }}" onclick="markNotificationRead('{{ $notification->id }}', null)" class="pointer float-start text-right small text-gray-500">
                                                {{ __('pages.mark_as_read') }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div id="no_notifications" class="dropdown-item d-flex align-items-center mb-3">{{ __('pages.no_notifications') }}</div>
                            @endif
                            <a class="dropdown-item text-center small text-gray-500"
                               href="#">{{ __('pages.see_all_notifications') }}</a>
                        </div>
                    </div>
                </li>

                <li id="show-users" class="nav-item dropdown no-arrow">
                    <div class="user-img-small" onclick="showUsers()" >
                        <img class="h-8 rounded-full no-lightbox" src="{{ Auth::user()->image ? Storage::disk('s3')->temporaryUrl(Auth::user()->image, now()->addMinute()) : asset('/img/no_image.png') }}" alt="User Profile Image">
                    </div>
{{--                    <button onclick="showUsers()" class="btn btn-neutral-square px-3">--}}
{{--                        <i class="fa-solid fa-users"></i> ({{ $users->where('last_access', '>', \Carbon\Carbon::now()->subMinutes(5))->count() - 1 }})--}}
{{--                    </button>--}}
                </li>
            </ul>
        </div>
    </nav>
    <!-- End of Navigation Bar -->

    @yield('navigation')

    <div id="lightbox" class="hidden">
        <img id="lightbox-img" src="">
        <div class="close-lightbox"><i class="fa-solid fa-x"></i></div>
    </div>

    <script src="{{ asset("/js/custom.js") }}"></script>
</body>

</html>
