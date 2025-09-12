@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Rol Yönetimi</h1>
            <a href="{{ route('admin.roles.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
                Yeni Rol Ekle
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <ul class="space-y-2">
                {{-- Ağaç yapısını başlatmak için partial view çağırıyoruz --}}
                @foreach ($roles as $role)
                    @include('admin.roles._role_tree_item', ['role' => $role])
                @endforeach
            </ul>
        </div>
    </div>
@endsection
