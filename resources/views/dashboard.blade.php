<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    @if(auth()->user()->hasRole('site-admin') && isset($stats))
                        <h3 class="text-lg font-semibold mb-4">Yönettiğiniz Sitelerin Genel Durumu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                            <div class="bg-blue-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Toplam Sakin</h4>
                                <p class="text-2xl font-bold">{{ $stats['totalResidents'] ?? '0' }}</p>
                            </div>

                            <div class="bg-green-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Toplam Daire/Birim</h4>
                                <p class="text-2xl font-bold">{{ $stats['totalUnits'] ?? '0' }}</p>
                            </div>

                            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Toplam Gelir</h4>
                                <p class="text-2xl font-bold">{{ $stats['totalIncome'] ?? '0' }} ₺</p>
                            </div>

                            <div class="bg-red-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Toplam Gider</h4>
                                <p class="text-2xl font-bold">{{ $stats['totalExpense'] ?? '0' }} ₺</p>
                            </div>

                            <div class="bg-indigo-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Bakiye</h4>
                                <p class="text-2xl font-bold">{{ $stats['balance'] ?? '0' }} ₺</p>
                            </div>

                            <div class="bg-pink-100 p-4 rounded-lg shadow">
                                <h4 class="text-gray-600">Ödenmemiş Borçlar</h4>
                                <p class="text-2xl font-bold">{{ $stats['totalDebts'] ?? '0' }} ₺</p>
                            </div>

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
