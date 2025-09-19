<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Yönetici Paneli') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bu Ayın Finansal Özeti</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Toplam Gelir:</span>
                            <span class="font-bold text-green-600">{{ number_format($total_income_this_month, 2) }} TL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Toplam Gider:</span>
                            <span class="font-bold text-red-600">{{ number_format($total_expense_this_month, 2) }} TL</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kasa Durumu:</span>
                            <span class="font-bold text-blue-600">{{ number_format($total_income_this_month - $total_expense_this_month, 2) }} TL</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Gecikmiş Borçlar</h3>
                    <div class="text-3xl font-bold text-center text-orange-500">
                        {{ $due_fees_count }}
                    </div>
                    <p class="text-center text-gray-500 mt-2">adet vadesi geçmiş borç bulunmaktadır.</p>
                    <div class="mt-4 text-center">
                        <a href="{{-- route('fees.index') --}}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Tümünü Gör</a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 col-span-1 md:col-span-2 lg:col-span-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Son Duyurular</h3>
                    <ul class="space-y-2">
                        @forelse($latest_announcements as $announcement)
                            <li class="border-b pb-2">
                                <p class="font-semibold">{{ $announcement->title }}</p>
                                <p class="text-xs text-gray-500">{{ $announcement->created_at->format('d.m.Y') }}</p>
                            </li>
                        @empty
                            <li>Son 5 duyuru bulunmamaktadır.</li>
                        @endforelse
                    </ul>
                    <div class="mt-4">
                        <a href="{{-- route('announcements.index') --}}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Tüm Duyurular</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
