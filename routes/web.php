<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
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
})->name('index');

Route::get('/auth/azure', function () {
    $minecraftUser = Socialite::driver('minecraft')->user();
    $user = User::updateOrCreate([
        'uuid' => $minecraftUser->uuid,
    ]);

    Auth::login($user, true);
    return redirect()->route('index');
});

Route::get('/perf-test', function () {
    return json_encode([
        'server' => $_SERVER,
        'time' => time(),
        'memory' => memory_get_usage(),
    ]);
})->name('perf-test');

Route::get('/auth/minecraft', function () {
    return Socialite::driver('minecraft')
        //->with(['prompt' => 'select_account'])
        ->redirect();
})->name('login-minecraft');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('index');
    })->name('dashboard');
});
