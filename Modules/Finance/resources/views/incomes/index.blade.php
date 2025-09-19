<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gelir Yönetimi</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('finance.incomes.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Yeni Gelir Ekle</a>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Açıklama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Site</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tutar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Tarih</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($incomes as $income)
                            <tr>
                                <td class="px-6 py-4">{{ $income->description }}</td>
                                <td class="px-6 py-4">{{ $income->site->name ?? 'Genel' }}</td>
                                <td class="px-6 py-4">{{ number_format($income->amount, 2) }} TL</td>
                                <td class="px-6 py-4">{{ $income->income_date->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $incomes->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
