@if(!Route::is('dashboard'))
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            @if(Auth::user()->privilege=="Administrator")
            <li class="breadcrumb-item">
                <a class="text-danger" href="{{route('dashboard')}}">Dashboard</a>
            </li>
            @endif
            @php $link = url('/'); @endphp
            @foreach(Request::segments() as $segment)
            @php $link .= "/" . Request::segment($loop->iteration); @endphp
            @if(rtrim(Request::route()->getPrefix(), '/') != $segment && ! preg_match('/[0-9]/', $segment))
            <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" aria-current="page">
                @if($loop->last)
                    @yield('page_name')
                @else
                    <a class="text-danger" href="{{$link}}">{{ucwords(str_replace('-',' ',$segment))}}</a>
                @endif
            </li>
            @endif
            @endforeach
        </ol>
    </nav>
    @if(Request::is('data-spp/*','data-kelas/*','data-siswa/*','data-petugas/*','entri-pembayaran/*'))
    <div class="my-3">
        <a class="small font-weight-bold text-danger" href="{{url()->previous()}}">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    @endif
@endif