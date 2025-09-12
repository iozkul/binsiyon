@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Kullanıcı Rol Yönetimi</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th>Ad Soyad</th>
                <th>Mevcut Rol(ler)</th>
                <th style="width: 300px;">Yeni Rol Ata</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->getRoleNames()->join(', ') }}</td>
                    <td>
                        <form action="{{ route('users.update_role', $user) }}" method="POST" class="d-flex">
                            @csrf
                            <select name="role" class="form-select form-select-sm me-2">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Güncelle</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
