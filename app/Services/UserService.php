<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        return User::create([
            ...$data,
            'password' => Hash::make($data['password'])
        ]);
    }
}
