<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bütçeler (İşletme Projeleri)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        @can('manage budgets')
                            <a href="{{ route('budgets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Yeni Bütçe Oluştur
                            </a>
                        @endcan
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bütçe Adı</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlangıç Tarihi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bitiş Tarihi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">İşlemler</span></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($budgets as $budget)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $budget->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $budget->start_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $budget->end_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($budget->status == 'approved') bg-green-100 text-green-800 @elseif($budget->status == 'pending_approval') bg-yellow-100 text-yellow-800 @elseif($budget->status == 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                        {{ $budget->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('budgets.show', $budget->id) }}" class="text-indigo-600 hover:text-indigo-900">Görüntüle</a>
                                    @can('manage budgets')
                                        <a href="{{ route('budgets.edit', $budget->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Düzenle</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
