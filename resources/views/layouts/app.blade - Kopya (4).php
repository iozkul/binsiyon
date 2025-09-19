<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - TEST MODU</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex">

{{-- Sidebar - TÜM YETKİ KONTROLLERİ GEÇİCİ OLARAK KALDIRILDI --}}
<div class="bg-dark text-white p-3 vh-100" style="width:250px;">
    <h4 class="mb-4">Yönetim (Test Modu)</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link text-white" href="#">- Test Link 1 -</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">- Test Link 2 -</a></li>
        <li class="nav-item">
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

{{-- Main Content --}}
<div class="flex-grow-1 p-4">
    <h2 class="mb-4">🏠 Dashboard</h2>
    <p>Uygulama test modunda çalışıyor. Eğer bu sayfayı görüyorsanız, hafıza hatası çözüldü ve sorun yetki kontrol direktiflerinden kaynaklanıyordu.</p>
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
