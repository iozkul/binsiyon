{{-- resources/views/admin/users/index.blade.php --}}

{{-- Layout'unuzun başlangıç kodları (extends, section vb.) --}}
@extends('layouts.app') {{-- Örnek layout adı --}}

@section('content')
    <div class="container">
        <h1>Kullanıcı Yönetimi</h1>

        {{-- Başarı veya hata mesajlarını göstermek için --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Roller</th>
                        <th>Paket</th>
                        <th>Durum</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{-- Döngüden önce koleksiyonun boş olup olmadığını kontrol etmek iyi bir pratiktir --}}
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{-- Rol isimlerini alıp virgülle birleştiriyoruz --}}
                                @if($user->roles->isNotEmpty())
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                @else
                                    Rol Atanmamış
                                @endif
                            </td>
                            <td>
                                @if($user->package)
                                    <span class="badge bg-success">{{ $user->package->name }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Paket Yok</span>
                                @endif
                            </td>
                            <td>
                                @if($user->banned_at)
                                    <span class="badge bg-danger">Engelli</span>
                                @else
                                    <span class="badge bg-primary">Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.assign-package.form', $user) }}" class="btn btn-sm btn-info">Paket Ata</a>
                                <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $user->banned_at ? 'btn-success' : 'btn-danger' }}">
                                        {{ $user->banned_at ? 'Engeli Kaldır' : 'Engelle' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Eğer $users koleksiyonu boşsa bu satır gösterilir --}}
                        <tr>
                            <td colspan="6" class="text-center">Gösterilecek kullanıcı bulunamadı.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sayfalama linklerini göstermek için --}}
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
{{-- Layout'unuzun bitiş kodları --}}
