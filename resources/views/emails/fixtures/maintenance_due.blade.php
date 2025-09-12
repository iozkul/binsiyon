<!DOCTYPE html>
<html>
<head>
    <title>Bakım Hatırlatması</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { padding: 20px; }
        .header { font-size: 24px; font-weight: bold; color: #d9534f; }
        .site-name { font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">Yaklaşan Bakım Uyarısı</div>
    <p>Merhaba,</p>
    <p>Aşağıda listelenen demirbaşların periyodik bakım zamanı gelmiş veya geçmiştir. Lütfen gerekli kontrolleri sağlayınız.</p>

    <div class="site-name">Site: {{ $site->name }}</div>

    <table>
        <thead>
        <tr>
            <th>Demirbaş Adı</th>
            <th>Marka / Model</th>
            <th>Planlanan Bakım Tarihi</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fixtures as $fixture)
            <tr>
                <td>{{ $fixture->name }}</td>
                <td>{{ $fixture->brand }} {{ $fixture->model }}</td>
                <td>{{ $fixture->next_maintenance_date->format('d/m/Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p class="footer">Bu bildirim Binsiyon sistemi tarafından otomatik olarak gönderilmiştir.</p>
</div>
</body>
</html>
