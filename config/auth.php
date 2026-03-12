<?php
return [
    'defaults' => [
        'guard' => 'siswa',
        'passwords' => 'admins',
    ],
    'guards' => [
        'web' => ['driver' => 'session', 'provider' => 'users'],
        'admin' => ['driver' => 'session', 'provider' => 'admins'],
        'guru' => ['driver' => 'session', 'provider' => 'gurus'],
        'siswa' => ['driver' => 'session', 'provider' => 'siswas'],
    ],
    'providers' => [
        'users' => ['driver' => 'eloquent', 'model' => App\Models\User::class],
        'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin::class],
        'gurus' => ['driver' => 'eloquent', 'model' => App\Models\Guru::class],
        'siswas' => ['driver' => 'eloquent', 'model' => App\Models\Siswa::class],
    ],
    'passwords' => [
        'admins' => ['provider' => 'admins', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'gurus' => ['provider' => 'gurus', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
        'siswas' => ['provider' => 'siswas', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60],
    ],
    'password_timeout' => 10800,
];
