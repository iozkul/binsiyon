@extends('layouts.app')
@section('content')
    <h1>Paketler Dashboard</h1>
    <div class="row">
        <div class="col-md-4"> <div class="card p-3 mb-3"> <h5>Toplam Paket</h5> <h2>{{ $stats['total_packages'] }}</h2> </div> </div>
        <div class="col-md-4"> <div class="card p-3 mb-3"> <h5>Toplam Özellik (Modül)</h5> <h2>{{ $stats['total_features'] }}</h2> </div> </div>
        <div class="col-md-4"> <div class="card p-3 mb-3"> <h5>Atanmış Kullanıcı</h5> <h2>{{ $stats['assigned_users'] }}</h2> </div> </div>
    </div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-primary mb-3">Yeni Paket Ekle</a>
    <table class="table">
        <thead> <tr> <th>Paket Adı</th> <th>Fiyat</th> <th>Özellik Sayısı</th> <th>İşlemler</th> </tr> </thead>
        <tbody>
        @foreach($packages as $package)
            <tr>
                <td>{{ $package->name }}</td>
                <td>{{ $package->price }} TL</td>
                <td>{{ $package->features_count }}</td>
                <td><a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-warning">Düzenle</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
