<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/test-logger', function () {
    Log::info('Test log message from route');
    return response()->json(['message' => 'Log entry created']);
});
