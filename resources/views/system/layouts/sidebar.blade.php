@extends('system.layouts.header')

@section('navigation')

    <!-- Page Wrapper -->
    <div id="wrapper" class="flex">
        <div id="cover"><div><img class="no-lightbox" src="{{asset('img/oaple.png')}}" alt="Oaple Logo"></div></div>

        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="p-3 bg-neutral-700 text-white text-sm">
            <ul id="accordionSidebar" class="flex flex-col">
                <!-- Nav Item - Dashboard -->
                <li class="p-3 rounded-md hover:bg-neutral-300">
                    <a class="nav-link collapsed" href="{{ route('home') }}">
                        <div class="flex-vertical-center no-wrap me-3">
                            <i class="fas fa-fw fa-flag"></i>
                            <span>{{ __('trans.dashboard') }}</span>
                        </div>
                    </a>
                </li>

                <!-- Nav Item - Bug Reports Menu -->
                <li class="p-3 rounded-md hover:bg-neutral-300">
                    <a href="{{ route('bug-reports.index') }}" class="nav-link collapsed" data-toggle="collapse" data-target="#collapse_contracts_menu"
                       aria-expanded="false" aria-controls="collapse_contracts_menu">
                        <div class="flex-vertical-center no-wrap me-3">
                            <i class="fas fa-fw fa-file-signature"></i>
                            <span>{{ __('trans.bug_reports') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End of Sidebar -->

        <div id="" class="flex flex-col flex-1">
            <!-- Content Wrapper -->
            <div id="" class="flex-1 min-h-screen bg-neutral-100">
                <main class="overflow-x">
                    @yield('content')
                </main>
            </div>
            <!-- End of Content Wrapper -->

            <!-- Footer -->
            <footer class="w-full text-center p-3">
                {{ __('pages.copyright') }} &copy {{ "Charitou Multimedia Solutions" }} {{ Carbon\Carbon::now()->format('Y') }}
            </footer>
        </div>
    </div>
    <!-- End of Page Wrapper -->

@endsection
