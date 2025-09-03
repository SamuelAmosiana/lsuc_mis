<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/check-admin', function () {
    $user = User::where('email', 'admin@lscollege.test')->first();
    
    if (!$user) {
        return 'Admin user not found';
    }
    
    return [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'password_set' => !empty($user->password),
        'created_at' => $user->created_at,
        'updated_at' => $user->updated_at,
    ];
});
