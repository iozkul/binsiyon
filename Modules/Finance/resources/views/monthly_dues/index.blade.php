<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Aylık Aidatlar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('finance.monthly-dues.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Yeni Aidat Ekle
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Site</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daire</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dönem</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Son Ödeme</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">İşlemler</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($monthlyDues as $due)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $due->site->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $due->apartment->number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $due->resident->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $due->period->format('F Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($due->amount, 2) }} TL</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $due->due_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($due->status == 'paid') bg-green-100 text-green-800 @endif
                                                @if($due->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                                @if($due->status == 'overdue') bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($due->status) }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('finance.monthly-dues.edit', $due) }}" class="text-indigo-600 hover:text-indigo-900">Düzenle</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        Kayıt bulunamadı.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $monthlyDues->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
