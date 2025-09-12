@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Yeni Rol Oluştur</h1>
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @include('admin.roles._form', ['role' => new \App\Models\Role(), 'buttonText' => 'Rolü Oluştur'])
            </form>
        </div>
    </div>
@endsection

