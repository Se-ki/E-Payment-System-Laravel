<?php

use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentPaymentRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentBalancePaymentController;
use App\Models\Description;
use Illuminate\Support\Facades\Auth;

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

Route::middleware(['auth', 'verified'])->group(function () {
    //these are the general purpose routes
    Route::get('/', fn () => view("dashboard"));

    Route::get('profile', fn () => view('profile.index'))->name('profile');
    Route::patch('profile/{id}', [ProfileController::class, 'update'])->name('student.profile.update');

    //this route is only for admin and students
    Route::get('records', [StudentPaymentRecordController::class, 'index'])->name("records.index");

    //student route
    Route::middleware('isStudent')->group(function () {
        // Route::get('records/{semester?}/{year?}', [StudentPaymentRecordController::class, 'index'])->name("records.index")->where(['semester' => '^[1-2]$']);
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index')->where(['semester' => '^[1-2]$']);
        Route::post('pay/{id}', [StudentPaymentRecordController::class, 'store'])->name('pay');
        Route::get('balance', [StudentBalancePaymentController::class, 'index'])->name("balance.index");
    });

    //this route is for Collector and Admin only    
    Route::get('payment-form/{payment:id}', [StudentBalancePaymentController::class, 'create'])->name("balance.create");
    Route::post('payment-form/{payment:id}', [StudentBalancePaymentController::class, 'store'])->name('balance.store');
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentBalancePaymentController::class, 'listOfStudent'])->name("balance.student.index");
        Route::get('payment-list/{student:username}', [StudentBalancePaymentController::class, 'listOfPayments'])->name("balance.student.payment.index");
        Route::get('balance/{student:username}', [StudentBalancePaymentController::class, 'show'])->name('balance.show');
    });
    Route::get('balance-form/{balance:id}', [StudentBalancePaymentController::class, 'edit'])->name('balance.edit');
    Route::patch('balance-form/{balance:id}', [StudentBalancePaymentController::class, 'update'])->name('balance.update');

    //collector route
    Route::middleware("isCollector")->group(function () {
        //route here
    });

    //admin route
    Route::middleware('isAdmin')->group(function () {
        //route here
        Route::get('students/list-of-payments', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments-create', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('descriptions', [DescriptionController::class, 'index'])->name('descriptions.index');
        Route::post('descriptions', [DescriptionController::class, 'store'])->name('descriptions.store');
    });

    Route::resource('payment', PaymentController::class);
    Route::resource('record', StudentPaymentRecordController::class);
    Route::resource('description', DescriptionController::class);
});
Route::middleware('auth')->prefix('user')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
