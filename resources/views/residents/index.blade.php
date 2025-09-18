<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tüm Sakinler') }}
        </h2>
    </x-slot>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Tüm Sakinler (Kullanıcılar)</span>
                    <div>
                        <a href="{{ route('residents.assign_roles_form') }}" class="btn btn-primary btn-sm">Yeni Üyelere Yetki Ver</a>
                        {{-- <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">Yeni Kullanıcı Ekle</a> --}}
                    </div>
                </div>

                <div class="card-body">
                    @if($residents->isEmpty())
                        <p class="text-center">Sistemde kayıtlı kullanıcı bulunmuyor.</p>
                    @else
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ad Soyad</th>
                                    <th>E-posta</th>
                                    <th>Roller</th>
                                    <th>Kayıt Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($residents as $resident)
                                <tr>
                                    <td>{{ $resident->id }}</td>
                                    <td>{{ $resident->name }}</td>
                                    <td>{{ $resident->email }}</td>
                                    <td>
                                        @if(!empty($resident->getRoleNames()))
                                            @foreach($resident->getRoleNames() as $roleName)
                                                <span class="badge bg-success">{{ $roleName }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $resident->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $resident->id) }}" class="btn btn-info btn-sm">Düzenle</a>
                                        <a href="#" class="btn btn-danger btn-sm">Sil</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
</x-admin-layout>>
