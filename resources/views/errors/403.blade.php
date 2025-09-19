<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yetkisiz Erişim</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="text-center">
    <h1 class="text-6xl font-bold text-red-500">403</h1>
    <p class="text-2xl font-light text-gray-800 mt-4">Yetkisiz Erişim</p>
    <p class="mt-2 text-gray-600">
        {{-- Controller'dan gelen özel hata mesajı burada gösterilecek --}}
        {{ $message ?? 'Bu sayfayı görüntüleme izniniz yok.' }}
    </p>
    <a href="{{ url()->previous() }}" class="mt-6 inline-block bg-blue-500 text-white font-semibold px-6 py-3 rounded-md">
        Geri Dön
    </a>
</div>
</body>
</html>
