@extends('layouts.app')
@section('content')
    <h1>Modül Yönetimi</h1>
    <p>Sistemde bulunan modülleri aktive veya deaktive edebilirsiniz.</p>
    <table class="table">
        <thead> <tr> <th>Modül</th> <th>Açıklama</th> <th>Durum</th> <th>İşlem</th> </tr> </thead>
        <tbody>
        @foreach($modules as $module)
            <tr>
                <td><strong>{{ $module['name'] }}</strong></td>
                <td>{{ $module['description'] }}</td>
                <td>
                    @if($module['is_active'])
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Pasif</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.modules.toggle', $module['name']) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $module['is_active'] ? 'btn-danger' : 'btn-success' }}">
                            {{ $module['is_active'] ? 'Deaktive Et' : 'Aktive Et' }}
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
