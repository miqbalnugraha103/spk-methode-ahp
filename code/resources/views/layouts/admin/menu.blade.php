<ul class="list">
    <li class="header">MAIN MENU</li>
    <li class="{{ Request::is('home') ? 'active' : ''}}"><a href="{{ url('/home') }}"><i class="fa fa-home"></i><span>Home</span></a></li> 
    <li class="{{ Request::is('admin/profile*') ? 'active' : ''}}"><a href="{{ url('/admin/profile') }}"><i class="fa fa-user"></i><span>Profile</span>
    </a></li>

    <li class="{{ Request::is('seleksi*') || Request::is('seleksi/*')  ? 'active' : ''}}">
        <a href="{{ url('/seleksi') }}"><i class="fa fa-database"></i><span>SELEKSI BARU</span>
        </a>
    </li>

    <li class="{{ (Request::is('admin/sales/*') || Request::is('admin/sales') || Request::is('admin/sales/*') || Request::is('admin/customer-profile') || Request::is('admin/customer-profile/*') || Request::is('admin/color') || Request::is('admin/color/*') || Request::is('admin/status-progress*')) || Request::is('admin/assignment/*') || Request::is('admin/brand*') || Request::is('admin/product*') || Request::is('admin/term-and-condition*') || Request::is('admin/term-and-condition/*')|| Request::is('admin/quote-template*') || Request::is('admin/quote-template/*') ? 'active' : ''}}">
        <a href="javascript:void(0);" class="menu-toggle">
            <i class="fa fa-list"></i>
            <span>DATA KRITERIA</span>
        </a>
        <ul class="ml-menu">
            <li class="{{ (Request::is('admin/sales') || Request::is('admin/sales/*') || Request::is('admin/sales/assignment/*')) ? 'active' : ''}}">
                <a href="{{ url('/admin/sales') }}"><i class="fa fa-database"></i><span>Data Kriteria</span>
                </a>
            </li>
            <li class="{{ (Request::is('admin/customer-profile') || Request::is('admin/customer-profile/*')) ? 'active' : ''}}">
                <a href="{{ url('/admin/customer-profile') }}"><i class="fa fa-database"></i><span>Kriteria Seleksi</span>
                </a>
            </li>
        </ul>
    </li>
    </li>
</ul>