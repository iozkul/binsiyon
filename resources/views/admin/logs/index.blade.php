<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Kullanıcı Aktivite Kayıtları</h2>

                <form method="GET" action="{{ route('admin.logs.index') }}" class="mb-6">
                    <x-primary-button>Filtrele</x-primary-button>
                </form>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left ...">Tarih</th>
                        <th class="px-6 py-3 bg-gray-50 text-left ...">İşlem Yapılan Kullanıcı</th>
                        <th class="px-6 py-3 bg-gray-50 text-left ...">İşlemi Yapan (Admin)</th>
                        <th class="px-6 py-3 bg-gray-50 text-left ...">Aksiyon Kodu</th>
                        <th class="px-6 py-3 bg-gray-50 text-left ...">Detaylar</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                        <tr>
                            <td class="px-6 py-4 ...">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 ...">{{ $log->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 ...">{{ $log->actor->name ?? 'Sistem' }}</td>
                            <td class="px-6 py-4 ...">{{ $log->action_code }}</td>
                            <td class="px-6 py-4 ...">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
