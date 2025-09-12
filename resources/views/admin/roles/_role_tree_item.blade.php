{{-- resources/views/admin/roles/_role_tree_item.blade.php --}}

<li>
    {{-- Rolün adını göster --}}
    <strong>{{ $role->name }}</strong>

    {{-- Eğer bu rolün alt rolleri (children) varsa, bu dosyayı tekrar çağırarak
         onları da listeleyelim (recursive yapı). --}}
    @if ($role->children->isNotEmpty())
        <ul>
            @each('admin.roles._role_tree_item', $role->children, 'role')
        </ul>
    @endif
</li>
