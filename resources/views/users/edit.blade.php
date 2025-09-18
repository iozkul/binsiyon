<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Tüm Sakinler') }}
        </h2>
    </x-slot>
            <div class="card">
                <div class="card-header">
                    Kullanıcı Düzenle: {{ $user->name }}
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Kullanıcı Bilgileri --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta Adresi</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <hr>

                        {{-- Bağımsız Birim Atama Alanı --}}
                        {{-- Roller --}}
                        <div class="mb-3">
                            <h5>Roller</h5>
                            @foreach($roles as $role)
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                           data-role-name="{{ $role->name }}" {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Yönetilecek Siteler (site-admin seçilince görünecek) --}}
                        <div id="managed_sites_div" class="mb-3" style="display: none;">
                            <label for="managed_sites" class="form-label"><strong>Yöneteceği Siteler</strong></label>
                            <select name="managed_sites[]" id="managed_sites" multiple class="form-select">
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ in_array($site->id, $userManagedSites) ? 'selected' : '' }}>{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Yönetilecek Bloklar (block-admin seçilince görünecek) --}}
                        <div id="managed_blocks_div" class="mb-3" style="display: none;">
                            <label for="managed_blocks" class="form-label"><strong>Yöneteceği Bloklar</strong></label>
                            <select name="managed_blocks[]" id="managed_blocks" multiple class="form-select">
                                @foreach($blocks as $block)
                                    <option value="{{ $block->id }}" {{ in_array($block->id, $userManagedBlocks) ? 'selected' : '' }}>{{ $block->site?->name }} - {{ $block->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        {{-- Özel Yetkiler --}}
                        <div class="mb-3">
                            <h5>Özel Yetkiler (Role ek olarak)</h5>
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                                {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="roles" class="block text-gray-700 font-bold mb-2">Kullanıcı Rolleri:</label>
                            <select name="roles[]" id="roles" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected(in_array($role->id, old('roles', $userRoles ?? [])))>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="unit_id" class="form-label"><strong>Sakin Olduğu Birim (Daire/Dükkan)</strong></label>
                            <select name="unit_id" id="unit_id" class="form-select">
                                <option value="">Birim Atanmamış</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $user->unit_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->block->site->name }} - {{ $unit->block->name }} - {{ $unit->name_or_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <hr>

                        {{-- YENİ EKLENEN MÜLK SAHİBİ ATAMA BÖLÜMÜ --}}
                        <div class="mb-3">
                            <label for="owned_units" class="form-label">
                                <strong>Mülk Sahibi Olduğu Birimler</strong>
                            </label>
                            <p class="text-muted small">
                                Bu kullanıcıyı mülk sahibi olarak atamak istediğiniz daireleri veya iş yerlerini seçin.
                                Bu seçim, kullanıcının `property-owner` rolüyle bu birimlerin finansal durumunu ve bildirimlerini görmesini sağlar.
                            </p>
                            <select name="owned_units[]" id="owned_units" multiple class="form-select" style="height: 200px;">
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{-- Eğer kullanıcı bu birime zaten sahipse, seçili olarak getir --}}
                                        {{ in_array($unit->id, $userOwnedUnits) ? 'selected' : '' }}>
                                        {{ $unit->block->site->name }} / {{ $unit->block->name }} / <strong>{{ $unit->name_or_number }}</strong>
                                    </option>
                                @endforeach
                            </select>
                            <small>Birden fazla seçmek için CTRL (veya Mac'te Command) tuşuna basılı tutun.</small>
                        </div>
                        @can('assignUnit')
                            <div class="mb-3">
                                <label for="unit_id" class="form-label"><strong>Sakin Olduğu Birim</strong></label>
                                <select name="unit_id" id="unit_id" class="form-select">
                                    {{-- ... option'lar ... --}}
                                </select>
                            </div>
                        @endcan
                        {{-- YENİ BÖLÜM BİTİŞİ --}}
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">İptal</a>
                    </form>
                </div>
            </div>

        <script>
            // Rol checkbox'larına event listener ekle
            document.querySelectorAll('.role-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', toggleManagedDivs);
            });

            function toggleManagedDivs() {
                // İlgili div'leri görünür/gizli yap
                const siteAdminChecked = document.querySelector('[data-role-name="site-admin"]').checked;
                document.getElementById('managed_sites_div').style.display = siteAdminChecked ? 'block' : 'none';

                const blockAdminChecked = document.querySelector('[data-role-name="block-admin"]').checked;
                document.getElementById('managed_blocks_div').style.display = blockAdminChecked ? 'block' : 'none';
            }

            // Sayfa yüklendiğinde mevcut duruma göre div'leri göster/gizle
            document.addEventListener('DOMContentLoaded', toggleManagedDivs);
        </script>
</x-admin-layout>>
