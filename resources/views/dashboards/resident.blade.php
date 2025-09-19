<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sakin Paneli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Güncel Borç Durumunuz</h3>
                    <div class="text-4xl font-bold text-center {{ $my_total_debt > 0 ? 'text-red-500' : 'text-green-500' }}">
                        {{ number_format($my_total_debt, 2) }} TL
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{-- route('user.ledger') --}}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Hesap Ekstresi</a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 row-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Site Duyuruları</h3>
                    <ul class="space-y-2">
                        @forelse($latest_announcements as $announcement)
                            <li class="border-b pb-2">
                                <p class="font-semibold">{{ $announcement->title }}</p>
                                <p class="text-xs text-gray-500">{{ $announcement->created_at->format('d.m.Y') }}</p>
                            </li>
                        @empty
                            <li>Sitede yeni bir duyuru bulunmamaktadır.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Son Hareketler</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($my_latest_fees as $fee)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $fee->description }}</p>
                                    <p class="text-sm text-gray-500">Son Ödeme: {{ $fee->due_date->format('d.m.Y') }}</p>
                                </div>
                                <div class="text-sm font-semibold {{ $fee->status == 'paid' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($fee->amount, 2) }} TL
                                </div>
                            </li>
                        @empty
                            <li>Hareket bulunmamaktadır.</li>
                        @endforelse
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
