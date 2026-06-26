<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return response()->json(['message' => 'BUNNA API']);
});

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello from Laravel!']);
});

Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');

// ✅ Temporary DB check
Route::get('/check-db', function () {
    try {
        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'");
        return response()->json($tables);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});