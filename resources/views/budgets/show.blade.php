<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $budget->name }} - Bütçe Detayları
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div><strong>Site:</strong> {{ $budget->site->name }}</div>
                        <div><strong>Yıl:</strong> {{ $budget->year }}</div>
                        <div><strong>Oluşturan:</strong> {{ $budget->createdBy->name }}</div>
                        <div><strong>Durum:</strong> {{ $budget->status }}</div>
                        <div class="md:col-span-2"><strong>Açıklama:</strong> {{ $budget->description }}</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-medium text-green-600 mb-2">Gelir Kalemleri</h3>
                            <div class="space-y-2">
                                @foreach($budget->incomeItems as $item)
                                    <div class="flex justify-between p-2 bg-green-50 rounded">
                                        <span>{{ $item->category }}: {{ $item->description }}</span>
                                        <span class="font-bold">{{ number_format($item->amount, 2, ',', '.') }} TL</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between p-2 bg-green-200 rounded mt-4 font-bold">
                                <span>TOPLAM GELİR</span>
                                <span>{{ number_format($budget->totalIncome(), 2, ',', '.') }} TL</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-red-600 mb-2">Gider Kalemleri</h3>
                            <div class="space-y-2">
                                @foreach($budget->expenseItems as $item)
                                    <div class="flex justify-between p-2 bg-red-50 rounded">
                                        <span>{{ $item->category }}: {{ $item->description }}</span>
                                        <span class="font-bold">{{ number_format($item->amount, 2, ',', '.') }} TL</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-between p-2 bg-red-200 rounded mt-4 font-bold">
                                <span>TOPLAM GİDER</span>
                                <span>{{ number_format($budget->totalExpense(), 2, ',', '.') }} TL</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-blue-100 rounded-lg text-center">
                        <h3 class="text-xl font-bold text-blue-800">
                            Bütçe Durumu: {{ number_format($budget->totalIncome() - $budget->totalExpense(), 2, ',', '.') }} TL
                        </h3>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('budgets.edit', $budget) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Düzenle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
