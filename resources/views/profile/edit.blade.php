@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Profil Bilgilerimi Düzenle</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">

                        @csrf
                            @method('patch')
                            {{-- Profil Fotoğrafı Yükleme Alanı --}}
                            <div class="mb-3">
                                <label for="photo" class="form-label">Profil Fotoğrafı</label>
                                <input id="photo" name="photo" type="file" class="form-control">
                            </div>
                            {{-- İsim --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Ad Soyad</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                            </div>

                            {{-- E-posta --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">E-posta Adresi</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <hr>
                            <h5>Ek Bilgiler</h5>

                            {{-- Telefon --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefon Numarası</label>
                                <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>

                            {{-- Eğitim Durumu --}}
                            <div class="mb-3">
                                <label for="education_status" class="form-label">Eğitim Durumu</label>
                                <select id="education_status" name="education_status" class="form-select">
                                    <option value="">Seçin...</option>
                                    <option value="ilkokul" {{ $user->education_status == 'ilkokul' ? 'selected' : '' }}>İlkokul</option>
                                    <option value="ortaokul" {{ $user->education_status == 'ortaokul' ? 'selected' : '' }}>Ortaokul</option>
                                    <option value="lise" {{ $user->education_status == 'lise' ? 'selected' : '' }}>Lise</option>
                                    <option value="lisans" {{ $user->education_status == 'lisans' ? 'selected' : '' }}>Lisans</option>
                                    <option value="yuksek_lisans" {{ $user->education_status == 'yuksek_lisans' ? 'selected' : '' }}>Yüksek Lisans</option>
                                </select>
                            </div>

                            <div class="d-flex align-items-center gap-4">
                                <button type="submit" class="btn btn-primary">Kaydet</button>

                                @if (session('status') === 'profile-updated')
                                    <p class="text-sm text-success">Kaydedildi.</p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
            </div>
        </div>
    </div>
@endsection
