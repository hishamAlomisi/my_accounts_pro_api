<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountCeilingController;
use App\Http\Controllers\AccountPhoneController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ReceiverController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\SpendController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


Route::get('login', [UserController::class, 'login']); 

// مسار عام بدون توكين

// مسار محمي يتطلب توكين

    Route::resource('account-ceilings', AccountCeilingController::class);
    Route::resource('account-phones', AccountPhoneController::class);
    Route::resource('account-type', App\Http\Controllers\AccountTypeController::class);
    Route::resource('accounts', AccountController::class);

Route::resource('users', UserController::class);
Route::resource('currencies', CurrencyController::class);
Route::resource('receiver', ReceiverController::class);
Route::resource('receives', ReceiveController::class);
Route::resource('spends', SpendController::class);
Route::resource('cards', App\Http\Controllers\CardController::class);
Route::resource('constraints', App\Http\Controllers\ConstraintController::class);
Route::resource('sell-currencies', App\Http\Controllers\SellCurrencyController::class);
Route::resource('buy-currencies', App\Http\Controllers\BuyCurrencyController::class);
Route::resource('documents', App\Http\Controllers\DocumentController::class);
Route::resource('entries', App\Http\Controllers\EntryController::class);
Route::resource('entries-details', App\Http\Controllers\EntryDetailController::class);
Route::resource('send-transfers', App\Http\Controllers\SendTransferController::class);
Route::resource('receive-transfers', App\Http\Controllers\ReceiveTransferController::class);
