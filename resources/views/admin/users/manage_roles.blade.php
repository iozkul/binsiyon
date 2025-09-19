<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">{{ $user->name }} için Rol Yönetimi</h2>
                <form action="{{ route('admin.users.assignRoles', $user->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                       @if(in_array($role->name, $userRoles)) checked @endif
                                       class="form-checkbox h-5 w-5 text-blue-600">
                                <label for="role_{{ $role->id }}" class="ml-2 text-gray-700">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <x-primary-button>
                            Rolləri Güncelle
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
