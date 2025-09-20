{{-- ESKİ YAPI (Bu satırları silin):
@extends('layouts.app')
@section('content')
--}}

{{-- YENİ YAPI (Bu satırları ekleyin): --}}
<x-admin-layout>
    {{-- Eğer bir sayfa başlığı (header) kullanıyorsanız, onu bu şekilde "header" isimli slota yerleştirin --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- BURASI, SİZİN DASHBOARD'UNUZUN GERÇEK İÇERİĞİNİN OLDUĞU YER --}}

                    <p>Hoş geldiniz, {{ Auth::user()->name }}!</p>
                    <p>Burası süper yönetici panelidir. Tüm siteyi buradan yönetebilirsiniz.</p>

                    {{-- Örnek İstatistik Kartları --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
                        <div class="bg-blue-100 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold">Toplam Kullanıcı</h3>
                            <p class="text-2xl">{{ $stats['total_users'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold">Toplam Site</h3>
                            <p class="text-2xl">{{ $stats['total_sites'] ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold">Okunmamış Mesaj</h3>
                            <p class="text-2xl">{{ $unreadMessagesCount ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold">Okunmamış Duyuru</h3>
                            <p class="text-2xl">{{ $unreadAnnouncementsCount ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h4 class="text-gray-500">Toplam Bağımsız Bölüm</h4>
                            <p class="text-3xl font-bold">{{ $stats['total_units'] }}</p>
                        </div>
                        <div class="bg-green-100 p-6 rounded-lg shadow">
                            <h4 class="text-green-700">Toplam Gelir</h4>
                            <p class="text-3xl font-bold text-green-800">{{ number_format($stats['total_income'], 2, ',', '.') }} ₺</p>
                        </div>
                        <div class="bg-red-100 p-6 rounded-lg shadow">
                            <h4 class="text-red-700">Toplam Gider</h4>
                            <p class="text-3xl font-bold text-red-800">{{ number_format($stats['total_expense'], 2, ',', '.') }} ₺</p>
                        </div>
                    </div>

                    {{-- ... Diğer dashboard bileşenleriniz buraya gelebilir ... --}}

                </div>
            </div>
        </div>
    </div>

    {{-- ESKİ YAPI (Bu satırı silin):
    @endsection
    --}}

    {{-- YENİ YAPI (Bu satırı ekleyin): --}}
</x-admin-layout>
