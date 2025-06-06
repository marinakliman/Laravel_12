<?php

use App\Http\Controllers\ProfileController;
use App\Mail\Welcome;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test-telegram', function () {
    Telegram::sendMessage([
        'chat_id' => env('TELEGRAM_CHANNEL_ID'),
        'parse_mode' => 'HTML',
        'text' => 'Произошло тестовое событие.'
    ]);

    return response()->json(['status' => 'success']);
});

Route::get('/test-email', function () {
    $email = env('USER_MAIL');
    Mail::to($email)->send(new Welcome(User::where('id', 1)->first()));
});
