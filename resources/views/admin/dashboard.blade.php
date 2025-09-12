@extends('layouts.app') {{-- Kendi ana layout'unuzu kullanın --}}

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">Yönetim Paneli - Genel Durum</h3>

        {{-- Üst Kısımdaki İstatistik Kartları --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Toplam Kullanıcı Sayısı</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                            </div>
                            <div class="col-auto">
                                {{-- FontAwesome ikonları için, projenize ekli değilse bu satırı silebilirsiniz --}}
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Toplam Site Sayısı</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_sites'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-building fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Buraya "Toplam Gelir", "Bekleyen Talep Sayısı" gibi başka kartlar eklenebilir --}}
        </div>


        {{-- Alt Kısımdaki Detaylı Listeler --}}
        <div class="row">

            {{-- Son Kaydolan Kullanıcılar Paneli --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Son Kaydolan Kullanıcılar</h6>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary">Tümünü Gör →</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($recent_users as $recent_user)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $recent_user->name }}</span>
                                    <small class="text-muted">{{ $recent_user->created_at->diffForHumans() }}</small>
                                </li>
                            @empty
                                <li class="list-group-item">Henüz yeni bir kullanıcı kaydı yok.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Son Eklenen Siteler Paneli --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success">Son Eklenen Siteler</h6>
                        <a href="{{ route('sites.index') }}" class="btn btn-sm btn-outline-success">Tümünü Gör →</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($recent_sites as $site)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $site->name }}</span>
                                    <small class="text-muted">{{ $site->created_at->diffForHumans() }}</small>
                                </li>
                            @empty
                                <li class="list-group-item">Henüz yeni bir site eklenmemiş.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- Buraya "Son Destek Talepleri", "Son Ödemeler" ve "Son Mesajlar" gibi paneller de eklenebilir --}}

    </div>
@endsection
