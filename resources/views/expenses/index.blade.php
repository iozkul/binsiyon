<tbody class="bg-white divide-y divide-gray-200">
@foreach ($expenses as $expense)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            @if ($expense->file_path)
                <a href="{{ asset('storage/' . $expense->file_path) }}" target="_blank" class="text-blue-500 hover:underline">
                    Faturayı Gör
                </a>
            @endif
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                @if($expense->status == 'approved') bg-green-100 text-green-800 @elseif($expense->status == 'pending') bg-yellow-100 text-yellow-800 @elseif($expense->status == 'rejected') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                {{ $expense->status }}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            @if($expense->status == 'pending')
                @can('approve expenses')
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('expenses.updateStatus', $expense) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="text-green-600 hover:text-green-900">Onayla</button>
                        </form>
                        <form action="{{ route('expenses.updateStatus', $expense) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="text-red-600 hover:text-red-900">Reddet</button>
                        </form>
                    </div>
                @endcan
            @endif
            <a href="{{ route('expenses.edit', $expense->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Düzenle</a>
        </td>
    </tr>
@endforeach
</tbody>
