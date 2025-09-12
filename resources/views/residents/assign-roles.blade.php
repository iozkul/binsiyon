@extends('layouts.app') @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">Yeni Üyelere Yetki Ver</div>

                <div class="card-body">
                    @if($users->isEmpty())
                        <p class="text-center">Yetki verilecek yeni kullanıcı bulunmuyor.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Kullanıcı Adı</th>
                                    <th>E-posta</th>
                                    <th>Kayıt Tarihi</th>
                                    <th style="width: 250px;">Yetki Ata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <form action="{{ route('residents.assign_role', $user->id) }}" method="POST" class="d-flex">
                                            @csrf
                                            <select name="role" class="form-control form-control-sm me-2">
                                                <option value="">Rol Seçin...</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">Ata</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection