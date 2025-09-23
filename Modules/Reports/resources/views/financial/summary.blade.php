<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finansal Raporlar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">{{ $summary['title'] }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-100 p-6 rounded-lg shadow">
                            <h4 class="text-green-700 font-bold">Toplam Gelir</h4>
                            <p class="text-3xl font-bold text-green-800">{{ number_format($summary['total_income'], 2, ',', '.') }} ₺</p>
                        </div>

                        <div class="bg-red-100 p-6 rounded-lg shadow">
                            <h4 class="text-red-700 font-bold">Toplam Gider</h4>
                            <p class="text-3xl font-bold text-red-800">{{ number_format($summary['total_expense'], 2, ',', '.') }} ₺</p>
                        </div>

                        <div class="p-6 rounded-lg shadow {{ $summary['balance'] >= 0 ? 'bg-blue-100' : 'bg-yellow-100' }}">
                            <h4 class="{{ $summary['balance'] >= 0 ? 'text-blue-700' : 'text-yellow-700' }} font-bold">Güncel Bakiye</h4>
                            <p class="text-3xl font-bold {{ $summary['balance'] >= 0 ? 'text-blue-800' : 'text-yellow-800' }}">
                                {{ number_format($summary['balance'], 2, ',', '.') }} ₺
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
