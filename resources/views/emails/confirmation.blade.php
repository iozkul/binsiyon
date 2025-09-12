<!DOCTYPE html>
<html>
<head>
    <title>Hesabınızı Onaylayın</title>
</head>
<body>
<h1>Merhaba {{ $user->name }},</h1>
<p>Binsiyon'a hoş geldiniz! Hesabınızı aktifleştirmek için lütfen aşağıdaki butona tıklayın.</p>
<a href="{{ route('user.confirm', $user->confirmation_token) }}" style="padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none;">
    Hesabımı Onayla
</a>
<p>Eğer buton çalışmazsa, aşağıdaki linki tarayıcınıza yapıştırabilirsiniz:</p>
<p>{{ route('user.confirm', $user->confirmation_token) }}</p>
</body>
</html>
