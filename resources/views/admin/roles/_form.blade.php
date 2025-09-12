@csrf
<div class="mb-4">
    <label for="name" class="block text-gray-700 font-bold mb-2">Rol Adı:</label>
    <input type="text" name="name" id="name" value="{{ old('name', $role->name ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
</div>

<div class="mb-4">
    <label for="parent_role_id" class="block text-gray-700 font-bold mb-2">Üst Rol (Hiyerarşi):</label>
    <select name="parent_role_id" id="parent_role_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
        <option value="">Yok (En Üst Seviye)</option>
        @foreach($roles as $parentRole)
            <option value="{{ $parentRole->id }}" @selected(old('parent_role_id', $role->parent_role_id ?? '') == $parentRole->id)>
                {{ $parentRole->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label for="role_type" class="block text-gray-700 font-bold mb-2">Rol Tipi:</label>
    <select name="role_type" id="role_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
        <option value="SYSTEM" @selected(old('role_type', $role->role_type ?? '') == 'SYSTEM')>Sistem (Binsiyon Personeli)</option>
        <option value="CLIENT" @selected(old('role_type', $role->role_type ?? '') == 'CLIENT')>Müşteri (Site Kullanıcısı)</option>
    </select>
</div>

<div class="mb-6">
    <label class="block text-gray-700 font-bold mb-2">Yetkiler:</label>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($permissions as $permission)
            <div class="flex items-start">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}" class="mt-1"
                    @checked(in_array($permission->id, old('permissions', $rolePermissions ?? [])))>
                <label for="perm_{{ $permission->id }}" class="ml-2">{{ $permission->name }}</label>
            </div>
        @endforeach
    </div>
</div>

<div class="flex justify-end">
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
        {{ $buttonText ?? 'Kaydet' }}
    </button>
</div>


