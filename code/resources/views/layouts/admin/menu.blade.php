<ul class="list">
    <li class="header">MAIN MENU
        @if(Auth::user()->role ==  \App\User::ROLE_SUPERADMIN)
            <b>(SUPERADMIN)</b>
        @elseif(Auth::user()->role ==  \App\User::ROLE_ADMIN)
            <b>(ADMIN)</b>
        @else
            <b>(SALES)</b>
        @endif
    </li>
    <li class="{{ Request::is('home') ? 'active' : ''}}"><a href="{{ url('/home') }}"><i class="fa fa-home"></i><span>Home</span></a></li> 

    <li class="{{ (Request::is('pengguna') || Request::is('pengguna/*') || Request::is('profile')) ? 'active' : ''}}">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="fa fa-list"></i>
            <span>USER ADMIN</span>
        </a>
        <ul class="ml-menu">
            <li class="{{ (Request::is('pengguna') || Request::is('pengguna/*')) ? 'active' : ''}}">
                <a href="{{ url('pengguna') }}"><i class="fa fa-database"></i><span>Daftar Penguna</span>
                </a>
            </li>
            <li class="{{ Request::is('profile*') ? 'active' : ''}}">
                <a href="{{ url('profile') }}"><i class="fa fa-user"></i><span>Profile</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('seleksi*') || Request::is('seleksi/*')  ? 'active' : ''}}">
        <a href="{{ url('/seleksi') }}"><i class="fa fa-database"></i><span>SELEKSI BARU</span>
        </a>
    </li>

    <li class="{{ (Request::is('kriteria') || Request::is('kriteria/*') || Request::is('kriteria-seleksi') || Request::is('kriteria-seleksi/*')) ? 'active' : ''}}">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="fa fa-list"></i>
            <span>DATA KRITERIA</span>
        </a>
        <ul class="ml-menu">
            <li class="{{ (Request::is('kriteria') || Request::is('kriteria/*')) ? 'active' : ''}}">
                <a href="{{ url('/kriteria') }}"><i class="fa fa-database"></i><span>Data Kriteria</span>
                </a>
            </li>
            <li class="{{ (Request::is('kriteria-seleksi') || Request::is('kriteria-seleksi/*')) ? 'active' : ''}}">
                <a href="{{ url('/kriteria-seleksi') }}"><i class="fa fa-database"></i><span>Kriteria Seleksi</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="{{ Request::is('alternatif*') || Request::is('alternatif/*')  ? 'active' : ''}}">
        <a href="{{ url('/alternatif') }}"><i class="fa fa-database"></i><span>DATA ALTERNATIF</span>
        </a>
    </li>

    <li class="{{ (Request::is('nilai-kriteria') || Request::is('nilai-kriteria/*') || Request::is('nilai-alternatif') || Request::is('nilai-alternatif/*') || Request::is('hasil-seleksi') || Request::is('hasil-seleksi/*')) ? 'active' : ''}}">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="fa fa-list"></i>
            <span>SELEKSI AHP</span>
        </a>
        <ul class="ml-menu">
            <li class="{{ (Request::is('nilai-kriteria') || Request::is('nilai-kriteria/*')) ? 'active' : ''}}">
                <a href="{{ url('/nilai-kriteria') }}"><i class="fa fa-database"></i><span>Nilai Kriteria</span>
                </a>
            </li>
            <li class="{{ (Request::is('nilai-alternatif') || Request::is('nilai-alternatif/*')) ? 'active' : ''}}">
                <a href="{{ url('/nilai-alternatif') }}"><i class="fa fa-database"></i><span>Nilai Alternatif</span>
                </a>
            </li>
            <li class="{{ (Request::is('hasil-seleksi') || Request::is('hasil-seleksi/*')) ? 'active' : ''}}">
                <a href="{{ url('/hasil-seleksi') }}"><i class="fa fa-database"></i><span>Hasil Seleksi</span>
                </a>
            </li>
        </ul>
    </li>
    <li class="{{ Request::is('logout')  ? 'active' : ''}}">
        <a href="{{ url('/logout') }}"><i class="fa fa-close"></i><span>Logout</span>
        </a>
    </li>
</ul>