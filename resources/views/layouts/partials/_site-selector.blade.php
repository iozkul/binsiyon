@php
    // Giriş yapmış kullanıcının erişebileceği siteleri al
    $userSites = auth()->user()->sites; // Veya yönettiği tüm siteleri getiren bir metod
    $activeSiteId = session('active_site_id');
@endphp

<div class="ms-3">
    <form action="{{ route('sites.switch') }}" method="POST" id="site-switcher-form">
        @csrf
        <select name="site_id" class="form-select form-select-sm" onchange="document.getElementById('site-switcher-form').submit();">
            <option value="">-- Bir Site Seçin --</option>

            {{-- Sadece super-admin bu seçeneği görebilir --}}
            @if(auth()->user()->hasRole('super-admin'))
                <option value="all" {{ $activeSiteId == 'all' ? 'selected' : '' }}>
                    Tüm Siteler Özet
                </option>
            @endif

            @foreach($userSites as $site)
                <option value="{{ $site->id }}" {{ $activeSiteId == $site->id ? 'selected' : '' }}>
                    {{ $site->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>
