<div class="bg-dark text-white p-3 vh-100" style="width: 280px; flex-shrink: 0; overflow-y: auto;">
    <h4 class="mb-4">
        <a href="{{ route('dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
            <i class="fas fa-tachometer-alt me-2"></i>
            <span>Binsiyon Panel</span>
        </a>
    </h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-secondary rounded' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>

        @canany(['manage sites', 'manage blocks', 'manage units'])
            <li class="nav-item">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#managementSubmenu" role="button" aria-expanded="false" aria-controls="managementSubmenu">
                    <i class="fas fa-cogs me-2"></i> Yönetim <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs(['sites.*', 'blocks.*', 'units.*']) ? 'show' : '' }}" id="managementSubmenu">
                    <ul class="nav flex-column ps-3">
                        @can('manage sites')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('sites.index') }}">Siteler</a></li>@endcan
                        @can('manage blocks')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('blocks.index') }}">Bloklar</a></li>@endcan
                        @can('manage units')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('units.index') }}">Birimler</a></li>@endcan
                        @can('manage residents')<li class="nav-item"><a class="nav-link text-white" href="{{ route('residents.index') }}">👥 Sakinler</a></li>@endcan
                    </ul>
                </div>
            </li>
        @endcanany
        @can('manage site settings')<li class="nav-item"><a class="nav-link text-white" href="{{ route('site-settings.index') }}">Site Ayarları</a></li>@endcan
        @canany(['manage finance', 'manage budgets', 'view reports', 'view incomes', 'create expenses'])
            <li class="nav-item">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#financeSubmenu" role="button" aria-expanded="false" aria-controls="financeSubmenu">
                    <i class="fas fa-lira-sign me-2"></i> Finans <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->routeIs(['finance.*', 'budgets.*', 'reports.*', 'incomes.*', 'expenses.*', 'fees.*']) ? 'show' : '' }}" id="financeSubmenu">
                    <ul class="nav flex-column ps-3">
                        @can('manage finance')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('finance.index') }}">Finans Paneli</a></li>@endcan
                        @can('manage budgets')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('budgets.index') }}">Bütçeler</a></li>@endcan
                        @can('view reports')<li class="nav-item"><a class="nav-link text-white-50" href="#">Raporlar</a></li>@endcan
                        @can('view incomes')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('incomes.index') }}">Gelirler</a></li>@endcan
                        @can('create expenses')<li class="nav-item"><a class="nav-link text-white-50" href="{{ route('expenses.index') }}">Giderler</a></li>@endcan
                        <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('finance.monthly-dues.index') }}">Aidatlar</a></li>
                        <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('fees.index') }}">Aidat Yönetimi</a></li>
                        <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('fee-templates.index') }}">Aidat Şablonları</a></li>
                        <li class="nav-item"><a class="nav-link text-white-50" href="{{ route('debts.index') }}">Borçlular Listesi</a></li>
                     </ul>
                </div>
            </li>
        @endcanany

        <li class="nav-item"><a class="nav-link text-white" href="#"><i class="fas fa-wrench me-2"></i> Arıza Takip</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#"><i class="fas fa-box me-2"></i> Demirbaşlar</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#"><i class="fas fa-archive me-2"></i> Paket Yönetimi</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#"><i class="fas fa-wifi me-2"></i> IoT Yönetimi</a></li>

    </ul>
</div>
