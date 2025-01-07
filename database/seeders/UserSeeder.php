<?php

namespace Database\Seeders;

use App\Models\Save;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User([
            "username" => "test_user",
            "email" => "max@test.test",
            "anonymous" => false,
            "email_verified_at" => Carbon::now(),
            "last_activity" => Carbon::now(),
            "password" => "password",
            "role_id" => 1
        ]);
        $user->save();
        User::factory()->count(20)->create();
    }
}
