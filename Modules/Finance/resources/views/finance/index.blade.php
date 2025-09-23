<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Site Yönetimi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Toplam Gelir</h3>
                        <p class="mt-1 text-3xl font-semibold text-green-600">{{ number_format($totalIncome, 2, ',', '.') }} ₺</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Toplam Ödenmiş Gider</h3>
                        <p class="mt-1 text-3xl font-semibold text-red-600">{{ number_format($totalExpense, 2, ',', '.') }} ₺</p>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium">Güncel Borç Stoku</h3>
                        <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ number_format($totalDebt, 2, ',', '.') }} ₺</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @can('manage finance')
                        <div class="border-b pb-4 mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Finansal İşlemler</h3>
                            <p class="text-sm text-gray-600 mt-1">Vadesi geçmiş tüm aidat borçlarına yasal oranda (%5) gecikme tazminatı uygulamak için butona tıklayın.</p>
                            <form action="{{ route('finance.calculate-late-fees') }}" method="POST" onsubmit="return confirm('Bu işlem geri alınamaz. Vadesi geçmiş tüm borçlara yasal gecikme faizi uygulanacaktır. Emin misiniz?');">
                                @csrf
                                <x-primary-button class="mt-2">
                                    Gecikme Faizlerini Hesapla ve Uygula
                                </x-primary-button>
                            </form>
                        </div>
                    @endcan

                    <h3 class="text-lg font-medium text-gray-900">Hızlı Erişim</h3>
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('incomes.index') }}" class="text-blue-500 hover:underline">Gelirleri Görüntüle</a>
                        <a href="{{ route('expenses.index') }}" class="text-blue-500 hover:underline">Giderleri Görüntüle</a>
                        <a href="{{ route('fees.index') }}" class="text-blue-500 hover:underline">Aidat ve Borçları Görüntüle</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>>
