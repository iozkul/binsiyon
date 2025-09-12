@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Rolü Düzenle: {{ $role->name }}</h1>
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @method('PUT')
                @include('admin.roles._form', ['buttonText' => 'Değişiklikleri Kaydet'])
            </form>
        </div>
    </div>
@endsection
