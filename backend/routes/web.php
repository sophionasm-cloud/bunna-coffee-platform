<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Test route to verify Laravel is working
Route::get('/hello', function () {
    return ['message' => 'Hello from Laravel!'];
});