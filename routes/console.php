<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('perkapalan:make-admin {username?} {email?}', function (): int {
    $attributes = [
        'name' => $this->ask('Nama lengkap admin'),
        'username' => $this->argument('username') ?: $this->ask('Username admin'),
        'email' => $this->argument('email') ?: $this->ask('Email admin'),
    ];
    $validator = Validator::make($attributes, [
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255', 'unique:users,username'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
    ]);

    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    $password = $this->secret('Password admin (minimal 12 karakter)');
    $passwordValidator = Validator::make([
        'password' => $password,
        'password_confirmation' => $this->secret('Ulangi password admin'),
    ], ['password' => ['required', 'confirmed', Password::min(12)]]);

    if ($passwordValidator->fails()) {
        foreach ($passwordValidator->errors()->all() as $error) {
            $this->error($error);
        }

        return 1;
    }

    User::query()->create([
        'name' => $attributes['name'],
        'nama_lengkap' => $attributes['name'],
        'username' => $attributes['username'],
        'email' => $attributes['email'],
        'password' => Hash::make($password),
        'role' => 'admin',
        'membership_status' => 'approved',
        'approved_at' => now(),
        'activated_at' => now(),
        'email_verified_at' => now(),
    ]);

    $this->info('Akun admin berhasil dibuat untuk '.$attributes['username'].'.');

    return 0;
})->purpose('Buat akun admin dengan prompt dan password yang tervalidasi');
