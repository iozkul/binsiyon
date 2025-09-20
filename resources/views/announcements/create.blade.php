@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Yeni Duyuru Oluştur</div>
            <div class="card-body">
                <form action="{{ route('announcements.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="site_id" class="form-label">Duyurunun Yapılacağı Site</label>
                        <select name="site_id" id="site_id" class="form-select" required>
                            <option value="">Lütfen bir site seçin...</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Duyuru Başlığı</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Duyuru İçeriği</label>
                        <textarea name="body" id="body" class="form-control" rows="5" required></textarea>
                    </div>

                    <hr>
                    <h5>Hedef Kitle</h5>
                    <p class="text-muted">Eğer hiç bir hedef seçmezseniz, duyuru seçilen sitedeki herkese gönderilir.</p>

                    <div class="mb-3">
                        <label for="target_roles" class="form-label">Hedef Roller</label>
                        <select name="target_roles[]" id="target_roles" multiple class="form-select" style="height: 120px;">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="target_users" class="form-label">Hedef Kişiler (Role Ek Olarak)</label>
                        <select name="target_users[]" id="target_users" multiple class="form-select" style="height: 120px;">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Duyuruyu Yayınla</button>
                </form>
                {{-- Formun altına veya üstüne script ekle --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('announcementForm');
                        const activeSiteId = {{ session('active_site_id') ?? 'null' }};
                        if (!form || !activeSiteId) return;

                        const draftKey = `draft_announcement_site_${activeSiteId}`;

                        const savedDraft = localStorage.getItem(draftKey);
                        if (savedDraft) {
                            if (confirm('Bu site için kaydedilmemiş bir duyuru taslağınız var. Yüklemek ister misiniz?')) {
                                const draftData = JSON.parse(savedDraft);
                                form.querySelector('[name="title"]').value = draftData.title || '';
                                form.querySelector('[name="body"]').value = draftData.body || '';
                            }
                        }

                        form.addEventListener('input', () => {
                            const formData = {
                                title: form.querySelector('[name="title"]').value,
                                body: form.querySelector('[name="body"]').value,
                            };
                            localStorage.setItem(draftKey, JSON.stringify(formData));
                        });

                        form.addEventListener('submit', () => {
                            localStorage.removeItem(draftKey);
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
