@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Rol Düzenle: {{ $role->name }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Rol Adı --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Rol Adı</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                    </div>

                    <hr>

                    {{-- Bu Role Ait Yetkiler --}}
                    <div class="mb-3">
                        <h5>Bu Role Atanacak Yetkiler</h5>
                        <p class="text-muted">Bu role sahip olan kullanıcıların hangi işlemleri yapabileceğini seçin.</p>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-4">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               id="perm_{{ $permission->id }}"
                                            {{-- Eğer rol bu yetkiye zaten sahipse, checkbox'ı işaretli getir --}}
                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Rolü Güncelle</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
