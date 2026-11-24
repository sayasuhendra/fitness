<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/support', 'support')->name('support');
Route::get('/reset-password/{token}', function (string $token) {
    return view('welcome');
})->name('password.reset');

Route::get('/member/{any?}', function (?string $any = null) {
    if ($any && file_exists($filePath = public_path("member/{$any}")) && ! is_dir($filePath)) {
        return response()->file($filePath);
    }

    $indexPath = public_path('member/index.html');
    if (! file_exists($indexPath)) {
        abort(404, 'Aplikasi Member PWA belum di-build.');
    }

    return response(file_get_contents($indexPath), 200, [
        'Content-Type' => 'text/html; charset=UTF-8',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
    ]);
})->where('any', '.*')->name('member.pwa');

