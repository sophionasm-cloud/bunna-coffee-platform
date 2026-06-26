<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'BUNNA API']);
});

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello from Laravel!']);
});

// ✅ Fixes "Route [login] not defined" error
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');