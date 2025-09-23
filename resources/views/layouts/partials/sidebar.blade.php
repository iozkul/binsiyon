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
        @canany(['manage finance', 'manage personnel', 'manage legal cases'])
            <li class="nav-item">
                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#financeSubmenu" role="button" aria-expanded="false" aria-controls="financeSubmenu">
                    <i class="fas fa-file-invoice-dollar me-2"></i> Finans <i class="fas fa-chevron-down float-end"></i>
                </a>
                <div class="collapse {{ request()->is('finance*') || request()->is('personnel*') || request()->is('legal*') ? 'show' : '' }}" id="financeSubmenu">
                    <ul class="nav flex-column ps-3">

                        @can('manage budgets')
                            <li class="nav-item">
                                <a href="{{ route('finance.budgets.index') }}" class="nav-link text-white {{ request()->is('finance/budgets*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Planlama (Bütçeler)</p>
                                </a>
                            </li>
                        @endcan

                        @canany(['view incomes', 'create expenses'])
                            <li class="nav-item">
                                <a class="nav-link text-white collapsed" data-bs-toggle="collapse" href="#operationSubmenu" role="button" aria-expanded="false" aria-controls="operationSubmenu">
                                    <i class="fas fa-cogs me-2"></i> Operasyon <i class="fas fa-chevron-down float-end"></i>
                                </a>
                                <div class="collapse {{ request()->is('finance/incomes*') || request()->is('finance/expenses*') ? 'show' : '' }}" id="operationSubmenu">
                                    <ul class="nav flex-column ps-3">
                                        @can('view incomes')
                                            <li class="nav-item"><a class="nav-link text-white {{ request()->is('finance/incomes*') ? 'active' : '' }}" href="{{ route('finance.incomes.index') }}">Gelirler</a></li>
                                        @endcan
                                        @can('create expenses')
                                            <li class="nav-item"><a class="nav-link text-white {{ request()->is('finance/expenses*') ? 'active' : '' }}" href="{{ route('finance.expenses.index') }}">Giderler</a></li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcanany

                        @can('view reports')
                            <li class="nav-item">
                                <a href="{{ route('reports.financial.summary') }}" class="nav-link text-white {{ request()->is('reports*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Takip & Raporlama</p>
                                </a>
                            </li>
                        @endcan

                        @can('manage vendors')
                            <li class="nav-item">
                                <a href="{{ route('finance.vendors.index') }}" class="nav-link text-white {{ request()->is('finance/vendors*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Varlıklar (Tedarikçi)</p>
                                </a>
                            </li>
                        @endcan

                        @can('manage personnel')
                            <li class="nav-item">
                                <a href="{{ route('personnel.employees.index') }}" class="nav-link text-white {{ request()->is('personnel*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Personel & Bordro</p>
                                </a>
                            </li>
                        @endcan

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
