<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            @role('site-admin|block-admin')
            <form action="{{ route('context.switchSite') }}" method="POST" class="d-flex align-items-center me-3">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">

                @if(Auth::user()->hasRole('site-admin') && $managedSites->count() > 0)
                    <label for="active_site_selector" class="form-label me-2 mb-0 fw-bold"><i class="fas fa-sync-alt"></i> Site Değiştir:</label>
                    <select name="site_id" id="active_site_selector" class="form-select form-select-sm" onchange="this.form.submit()">
                        @foreach($managedSites as $site)
                            <option value="{{ $site->id }}" {{ session('active_site_id') == $site->id ? 'selected' : '' }}>
                                {{ $site->name }}
                            </option>
                        @endforeach
                    </select>
                @elseif(Auth::user()->hasRole('block-admin') && $managedBlocks->count() > 0)
                    {{-- Blok seçimi için benzer bir yapı buraya eklenebilir --}}
                @endif
            </form>
            @endrole
        </div>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">

                        <li class="nav-item dropdown me-2">
                            <a href="#" class="nav-link position-relative" id="announcementsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bullhorn"></i>
                                @if($unreadAnnouncementsCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6em;">
                                {{ $unreadAnnouncementsCount }}
                            </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="announcementsDropdown">
                                <li class="dropdown-header">Son Duyurular</li>
                                @forelse($latestAnnouncements as $announcement)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('announcements.show', $announcement->id) }}">
                                            <p class="fw-bold mb-0 text-truncate">{{ $announcement->title }}</p>
                                            <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item text-muted">Yeni duyuru yok.</span></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('announcements.index') }}">Tümünü Göster</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown me-3">
                            <a href="#" class="nav-link position-relative" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-envelope"></i>
                                @if($unreadMessagesCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark" style="font-size: 0.6em;">
                                {{ $unreadMessagesCount }}
                            </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="messagesDropdown">
                                <li class="dropdown-header">Son Mesajlar</li>
                                @forelse($latestMessages as $message)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('messages.show', $message->id) }}">
                                            <p class="fw-bold mb-0 text-truncate">Kimden: {{ $message->sender->name }}</p>
                                            <small class="text-muted text-truncate d-block">{{ $message->body }}</small>
                                        </a>
                                    </li>
                                @empty
                                    <li><span class="dropdown-item text-muted">Yeni mesaj yok.</span></li>
                                @endforelse
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('messages.index') }}">Tümünü Göster</a></li>
                            </ul>
                        </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Çıkış Yap</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
