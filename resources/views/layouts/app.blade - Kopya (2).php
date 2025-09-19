<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">

    {{-- Sidebar --}}
    <div class="bg-dark text-white p-3 vh-100" style="width:250px;">
        <h4 class="mb-4">Yönetim</h4>
        <ul class="nav flex-column">

            @can('manage sites')
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('sites.index') }}">🏢 Siteler</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('blocks.index') }}">🏢 Bloklar</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('units.index') }}">🏠 Birimler (Daire/İş Yeri)</a></li>
            @endcan
                @role('super-admin|site-admin|block-admin')
                @can('manage residents')
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('residents.index') }}">👥 Sakinler</a>
                    </li>
                @endcan
                @endrole

                @hasanyrole('super-admin|site-admin|accountant')
                <!--<x-nav-link :href="route('finance.monthly-dues.index')" :active="request()->routeIs('finance.*')">
                    Finans Yönetimi
                </x-nav-link>-->

                @endhasanyrole

                @hasanyrole('resident|property_owner')
                <x-nav-link :href="route('my-finances.index')" :active="request()->routeIs('my-finances.*')">
                    Borçlarım ve Ödemelerim
                </x-nav-link>
                @endhasanyrole

                @can('viewAny', App\Models\Site::class)
                    <x-nav-link :href="route('sites.index')" :active="request()->routeIs('sites.*')">
                        Site Yönetimi
                    </x-nav-link>
                @endcan
            @can('manage finance')

                <li class="nav-item">
                    {{-- Bu link, alt menüyü açıp kapatan ana başlık olacak --}}
                    <a class="nav-link text-white" href="#financeSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="financeSubmenu">
                        💰 Finans Yönetimi
                    </a>
                    {{-- Alt menü içeriği bu div içinde --}}
                    <div class="collapse" id="financeSubmenu">
                        <ul class="nav flex-column ms-3"> {{-- ms-3 ile içeriden başlatıyoruz --}}
                            <li class="nav-item">
                                @if (Route::has('finance.monthly-dues.index'))
                                    <a class="nav-link text-white py-1" href="{{ route('finance.monthly-dues.index') }}">Aylık Aidatlar</a>

                                @endif
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white py-1" href="{{ route('fees.index') }}">Aidat Yönetimi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white py-1" href="{{ route('fee-templates.index') }}">Aidat Şablonları</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white py-1" href="{{ route('debts.index') }}">Borçlular Listesi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white py-1" href="{{ route('incomes.index') }}">Gelirler</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white py-1" href="{{ route('expenses.index') }}">Giderler</a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endcan

            <li class="nav-item"><a class="nav-link text-white" href="{{ route('announcements.index') }}">📢 Duyurular</a></li>

            {{-- Gelişmiş Modüller --}}
            @can('use reservations')
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('reservations.index') }}">📅 Rezervasyon</a></li>
            @endcan
            @can('use maintenance')
                <li class="nav-item"><a class="nav-link text-white" href="#">🔧 Arıza Takibi</a></li>
            @endcan
            @can('use maintenance')
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('fixtures.index') }}">🔧 Demirbaş Yönetimi</a>
                </li>
            @endcan

            @can('use iot')
                <li class="nav-item"><a class="nav-link text-white" href="#">🔌 IoT</a></li>
            @endcan
            @can('use packages')
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.packages.index') }}">📦 Paket Yönetimi</a></li>
            @endcan
            <div class="mt-auto"> {{-- Bu div, içeriği en alta yaslar --}}
                <hr class="text-secondary">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('profile.edit') }}">
                            👤 Profilim
                        </a>
                    </li>
                    <li class="nav-item">
                        {{-- Çıkış işlemi bir form ile yapılmalıdır --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="nav-link text-white" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 Çıkış Yap
                            </a>
                        </form>
                    </li>
                </ul>
            </div>
        </ul>
    </div>

    {{-- Main Content --}}
    <div class="flex-grow-1 p-4">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            @include('layouts.navigation') @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
        <h2 class="mb-4">🏠 Dashboard</h2>
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
