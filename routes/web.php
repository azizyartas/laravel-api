<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function () {
    $credentails = [
        'email' => 'admin@gmail.com',
        'password' => 'password',
    ];

    if (!Auth::attempt($credentails)) {
        $user = new User();

        $user->name = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->password = Hash::make($credentails['password']);

        $user->save();
        if (Auth::attempt($credentails)) {
            $user = Auth::user();

            $adminToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
            $updateToken = $user->createToken('update-token', ['create', 'update']);
            $basicToken = $user->createToken('bosic-token');

            return [
                'admin' => $adminToken->plainTextToken,
                'update' => $updateToken->plainTextToken,
                'basic' => $basicToken->plainTextToken,
            ];
        }
    }
});
