<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Borçlarım ve Ödemelerim
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dönem</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tutar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Son Ödeme Tarihi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($monthlyDues as $due)
                            <tr>
                                <td class="px-6 py-4">{{ $due->period->format('F Y') }}</td>
                                <td class="px-6 py-4">{{ number_format($due->amount, 2) }} TL</td>
                                <td class="px-6 py-4">{{ $due->due_date->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($due->status == 'paid') bg-green-100 text-green-800 @endif
                                        @if($due->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                        @if($due->status == 'overdue') bg-red-100 text-red-800 @endif">
                                        {{ $due->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($due->status != 'paid')
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Ödeme Yap</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $monthlyDues->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
