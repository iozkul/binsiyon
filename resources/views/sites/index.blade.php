@extends('layouts.app')
@section('content')
<h2>Siteler</h2>
<a href="{{ route('sites.create') }}" class="btn btn-primary mb-3">Yeni Site</a>
<table class="table">
    <thead>
        <tr><th>ID</th><th>Ad</th><th>Adres</th><th>İşlemler</th></tr>
    </thead>
    <tbody>
        @foreach($sites as $site)
        <tr>
            <td>{{ $site->id }}</td>
            <td>{{ $site->name }}</td>
            <td>{{ $site->address }}</td>
            <td>
			    <a href="{{ route('sites.show', $site->id) }}" class="btn btn-info btn-sm">İncele</a>
                <a href="{{ route('sites.edit',$site->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                <form action="{{ route('sites.destroy',$site->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
