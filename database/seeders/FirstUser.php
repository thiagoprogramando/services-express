<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FirstUser extends Seeder {
    
    public function run(): void {
        DB::table('users')->insertGetId([
            'uuid'          => Str::uuid(),
            'name'          => 'Admin',
            'email'         => 'admin@admin.com',
            'password'      => Hash::make('123456'),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
