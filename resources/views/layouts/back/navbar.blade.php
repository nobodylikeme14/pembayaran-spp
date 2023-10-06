<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars text-danger"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow my-auto">
            <span class="mr-3 ml-4 d-sm-inline small font-weight-bold text-uppercase text-danger">
                {{Auth::user()->privilege}}
            </span>
        </li>
        <div class="topbar-divider mx-1 d-sm-block"></div>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-sm-inline text-gray-600 small font-weight-bold">
                    {{ Auth::user()->nama }}
                </span>
                <img class="img-profile rounded-circle my-auto" src="{{asset('assets/img/profile-pic.svg')}}">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                @if(Auth::user()->privilege == "Administrator")
                <a class="dropdown-item" href="{{route('akun')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Info Akun
                </a>
                @endif
                <a class="dropdown-item" href="{{route('logout')}}">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout
                </a>
            </div>
        </li>
    </ul>
</nav>