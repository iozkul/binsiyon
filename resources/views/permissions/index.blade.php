@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Tüm Yetkiler</h3>
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">Yeni Yetki Ekle</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>Yetki Adı</th><th style="width: 150px;">İşlemler</th></tr></thead>
                    <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Bu yetkiyi silmek, onu kullanan rollerden de kaldıracaktır. Emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
