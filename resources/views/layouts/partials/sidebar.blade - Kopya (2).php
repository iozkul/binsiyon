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
                        {{-- Diğer menüler... --}}
                        @can('manage finance')
                            <li class="nav-item has-treeview {{ request()->is('finance*') || request()->is('personnel*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Finans Yönetimi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">

                                    @can('manage budgets')
                                        <li class="nav-item">
                                            <a href="{{ route('finance.budgets.index') }}" class="nav-link {{ request()->is('finance/budgets*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Planlama (Bütçeler)</p>
                                            </a>
                                        </li>
                                    @endcan

                                    {{--
                                    <li class="nav-item">
                                        <a href="{{ route('finance.incomes.index') }}" class="nav-link {{ request()->is('finance/incomes*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Gelirler</p>
                                        </a>
                                    </li>
                                    @can('manage expenses')
                                        <li class="nav-item">
                                            <a href="{{ route('finance.expenses.index') }}" class="nav-link {{ request()->is('finance/expenses*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Giderler</p>
                                            </a>
                                        </li>
                                    @endcan
                                    <li class="nav-item">
                                        <a href="{{ route('finance.payments.index') }}" class="nav-link {{ request()->is('finance/payments*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Tahsilatlar</p>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="{{ route('finance.bank-accounts.index') }}" class="nav-link {{ request()->is('finance/bank-accounts*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Banka & Kasa Hesapları</p>
                                        </a>
                                    </li>
                                    @can('manage vendors')
                                        <li class="nav-item">
                                            <a href="{{ route('finance.vendors.index') }}" class="nav-link {{ request()->is('finance/vendors*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Tedarikçiler</p>
                                            </a>
                                        </li>
                                    @endcan--}}
                                </ul>
                            </li>
                        @endcan

                        @can('manage payrolls')
                            <li class="nav-item has-treeview {{ request()->is('hr*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('hr*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Personel Yönetimi
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('hr.employees.index') }}" class="nav-link {{ request()->is('hr/employees*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Personel Listesi</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('hr.payrolls.index') }}" class="nav-link {{ request()->is('hr/payrolls*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Bordrolar</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        @can('view financial reports')
                            <li class="nav-item has-treeview {{ request()->is('reports*') || request()->is('legal*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('reports*') || request()->is('legal*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-search-dollar"></i>
                                    <p>
                                        Takip & Raporlama
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ route('reports.financial.summary') }}" class="nav-link {{ request()->is('reports/financial*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Finansal Raporlar</p>
                                        </a>
                                    </li>
                                    @can('manage legal cases')
                                        <li class="nav-item">
                                            <a href="{{ route('legal.debt-collection.index') }}" class="nav-link {{ request()->is('legal/debt-collection*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Borçlu Takibi</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('legal.decision-ledger.index') }}" class="nav-link {{ request()->is('legal/decision-ledger*') ? 'active' : '' }}">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Karar Defteri</p>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
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
