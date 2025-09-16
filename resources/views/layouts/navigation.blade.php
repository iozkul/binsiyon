<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Sol Menü Linkleri (Dashboard vb.) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @can('manage budgets')
                        <x-nav-link :href="route('budgets.index')" :active="request()->routeIs('budgets.*')">
                            {{ __('Bütçeler') }}
                        </x-nav-link>
                    @endcan

                    @can('view reports')
                        <x-nav-link :href="route('reports.income-expense')" :active="request()->routeIs('reports.*')">
                            {{ __('Raporlar') }}
                        </x-nav-link>
                    @endcan

                @if(Auth::check() && Auth::user()->is_admin)
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index')">
                                {{ __('Kullanıcı Yönetimi') }}
                            </x-nav-link>

                            <x-nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.index')">
                                <i class="nav-icon fas fa-user-shield"></i>
                                {{ __('Rol Yönetimi') }}
                            </x-nav-link>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sağ Taraftaki İkon Menüleri -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- Duyurular Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @if(isset($unreadAnnouncementsCount) && $unreadAnnouncementsCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">{{ $unreadAnnouncementsCount }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 font-bold border-b">Duyurular</div>
                            {{-- Buraya son duyuruların bir listesi gelecek --}}
                            <a href="{{ route('announcements.index') }}" class="position-relative">
                                <i class="fas fa-bullhorn"></i> @if(isset($unreadAnnouncementsCount) && $unreadAnnouncementsCount > 0)
                                    <span class="notification-dot blink"></span>
                                @endif
                            </a>

                            <a href="{{ route('messages.index') }}" class="position-relative">
                                <i class="fas fa-envelope"></i>
                                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                    <span class="notification-dot blink"></span>
                                @endif
                            </a>
                            <x-dropdown-link href="#">Duyuru 1 (Okunmamış)</x-dropdown-link>
                            <x-dropdown-link href="#">Duyuru 2 (Okunmuş)</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <x-dropdown-link href="{{ route('announcements.index') }}">Tüm Duyuruları Gör</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mesajlar Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2">{{ $unreadMessagesCount }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 font-bold border-b">Mesajlar</div>
                            {{-- Buraya son mesajların bir listesi gelecek --}}
                            <x-dropdown-link href="#">Site Yöneticisinden yeni mesaj</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <x-dropdown-link href="{{ route('messages.index') }}">Gelen Kutusuna Git</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Profil Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out">
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url ?? '[https://ui-avatars.com/api/?name=](https://ui-avatars.com/api/?name=)' . urlencode(Auth::user()->name) . '&background=random' }}" alt="{{ Auth::user()->name }}" />
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 border-b">
                                <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->getRoleNames()->join(', ') }}</div>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profilimi Düzenle') }}</x-dropdown-link>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Çıkış Yap') }}</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">...</div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
        @if(auth()->user() && auth()->user()->hasRole('super-admin'))
            <x-nav-link :href="route('users.manage_roles')" :active="request()->routeIs('users.manage_roles')">
                {{ __('Kullanıcı Yönetimi') }}
            </x-nav-link>
    @endif
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
