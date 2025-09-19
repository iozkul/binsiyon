<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException; // Bunu ekleyin
use Illuminate\Http\Request; // Bunu ekleyin

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // YETKİLENDİRME HATALARINI YAKALAMAK İÇİN BU BLOĞU EKLEYİN
        $this->renderable(function (AuthorizationException $e, Request $request) {
            // Policy'den dönen özel mesajı al
            $responseMessage = $e->response()?->message() ?: $e->getMessage();

            // Eğer özel bir mesaj tanımlanmamışsa, genel bir mesaj oluştur
            $customMessage = $responseMessage === 'This action is unauthorized.'
                ? 'Bu işlemi gerçekleştirmek için gerekli yetkiye sahip değilsiniz.'
                : $responseMessage;

            // Eğer talep JSON bekliyorsa JSON formatında hata döndür
            if ($request->expectsJson()) {
                return response()->json(['message' => $customMessage], 403);
            }

            // Aksi halde hata sayfasıyla birlikte mesajı göster
            return response()->view('errors.403', ['message' => $customMessage], 403);
        });
    }
}
