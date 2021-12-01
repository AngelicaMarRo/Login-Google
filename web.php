<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/login-google', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();
    
    $userExists = User ::where('external_id', $user->id)->where('external_auth', 'google')->first();

    if($userExists){
        Auth::login($userExists);
    }else {
        $userNew = User::create([
           'name' => $user->name,
           'email' => $user->email,
           'avatar' => $user->avatar,
           'external_id' => $user->id,
           'external_auth' => 'google',
        ]);

        Auth::login($userNew);
    }
     
    return redirect('/home');
    // $user->token
});