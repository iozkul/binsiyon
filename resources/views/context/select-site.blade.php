<x-guest-layout>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Lütfen Bir Site Seçin</h4>
            <p class="text-center text-muted">İşlem yapmaya devam etmek için yönettiğiniz sitelerden birini seçmeniz gerekmektedir.</p>

            <form method="POST" action="{{ route('context.switchSite') }}">
                @csrf
                <div class="mb-3">
                    <label for="site_id" class="form-label">Yönettiğim Siteler</label>
                    <select name="site_id" id="site_id" class="form-select" required>
                        @foreach($managedSites as $site)
                            <option value="{{ $site->id }}">{{ $site->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Devam Et</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
