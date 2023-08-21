<ul class="navbar-nav bg-danger sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#!">
        <div class="sidebar-brand-icon">
            <i class="fab fa-pied-piper"></i>
        </div>
        <div class="sidebar-brand-text h5 text-capitalize font-weight-bold my-auto ml-3">
            {{ config('app.name') }}
        </div>
    </a>
    <hr class="sidebar-divider my-0">
    @if(Auth::user()->privilege == "Administrator")
    <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('dashboard')}}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Data</div>
    <li class="nav-item {{ Request::is('data-spp*','data-kelas*','data-siswa*','data-petugas*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" 
        aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-database"></i>
            <span>Master Data</span>
        </a>
        <div id="collapseTwo" class="collapse {{ Request::is('data-spp*','data-kelas*','data-siswa*','data-petugas*') ? 'show' : '' }}" 
        aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Data:</h6>
                <a class="collapse-item {{ Request::is('data-spp*') ? 'active' : '' }}" 
                href="{{route('spp')}}">Data SPP</a>
                <a class="collapse-item {{ Request::is('data-kelas*') ? 'active' : '' }}" 
                href="{{route('kelas')}}">Data Kelas</a>
                <a class="collapse-item {{ Request::is('data-siswa*') ? 'active' : '' }}" 
                href="{{route('siswa')}}">Data Siswa</a>
                <a class="collapse-item {{ Request::is('data-petugas*') ? 'active' : '' }}" 
                href="{{route('petugas')}}">Data Petugas</a>
            </div>
        </div>
    </li>
    @endif
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Pembayaran</div>
    <li class="nav-item {{ Request::is('entri-pembayaran*','histori-pembayaran*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Master Pembayaran</span>
        </a>
        <div id="collapsePages" class="collapse {{ Request::is('entri-pembayaran*','histori-pembayaran*') ? 'show' : '' }}" 
        aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pembayaran:</h6>
                @if(Auth::user()->privilege == "Administrator" || Auth::user()->privilege == "Petugas")
                <a class="collapse-item {{ Request::is('entri-pembayaran*') ? 'active' : '' }}" 
                href="{{route('entri_pembayaran')}}">Entri Pembayaran</a>
                @endif
                <a class="collapse-item {{ Request::is('histori-pembayaran*') ? 'active' : '' }}" 
                href="{{route('histori_pembayaran')}}">Histori Pembayaran</a>
            </div>
        </div>
    </li>
    @if(Auth::user()->privilege == "Administrator")
    <hr class="sidebar-divider">
    <div class="sidebar-heading">Laporan</div>
    <li class="nav-item {{ Request::is('laporan') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('laporan')}}">
            <i class="fas fa-print"></i>
            <span>Laporan</span></a>
    </li>
    @endif
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>