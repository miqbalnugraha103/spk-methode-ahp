<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title>{{ config('app.name', 'SPK Metode AHP') }} - @yield('title')</title>
    <!-- Favicon-->
    <link rel="icon" href="{{ URL('/') }}/images/favicon_ajd.ico" type="image/x-icon">

    @include('layouts.admin.header')

</head>

<body class="theme-blue">
<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="preloader">
            <div class="spinner-layer pl-blue">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <p>Please wait...</p>
    </div>
</div>
<!-- #END# Page Loader -->
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<!-- #END# Overlay For Sidebars -->
<!-- Search Bar -->
<div class="search-bar">
    <div class="search-icon">
        <i class="material-icons">search</i>
    </div>
    <input type="text" placeholder="START TYPING...">
    <div class="close-search">
        <i class="material-icons">close</i>
    </div>
</div>
<!-- #END# Search Bar -->
<!-- Top Bar -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
            <a href="javascript:void(0);" class="bars"></a>
            <a class="navbar-brand" href="{{ url('/admin') }}">Sistem Pendukung Keputusan Seleksi Alternatif Metode AHP</a>
        </div>
    </div>
</nav>
<!-- #Top Bar -->
<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="{{ url('/') }}/images/user.png" width="48" height="48" alt="User" />
            </div>
            <div class="info-container">
                <a href="{{ url('/admin/logout') }}" class="btn bg-blue waves-effect" title="Keluar">
                    <i class="fa fa-exit" aria-hidden="true"></i> Keluar
                </a>
                {{-- <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</div> --}}
                {{-- <div class="email">{{ Auth::user()->email }}</div> --}}
                {{-- <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="{{ url('/admin/profile') }}"><i class="material-icons">person</i>Profile</a></li>
                        <li><a href="{{ url('/admin/logout') }}"><i class="material-icons">input</i>Log Out</a></li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            @include('layouts.admin.menu')
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; Copyright {{ date('Y') }} <a href="javascript:void(0);">CRM - AJD</a>.
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
    <!-- Right Sidebar -->
    <aside id="rightsidebar" class="right-sidebar">
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active in active" id="skins">

            </div>
        </div>
    </aside>
    <!-- #END# Right Sidebar -->
</section>

<section class="content">
    @yield('content')
</section>

@include('layouts.admin.script')
@stack('script')

</body>

</html>