<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        User::create([
            'name'     =>  $faker->name,
            'email'    => 'test@example.com',
            'password' => bcrypt('123456'),
        ]);
        $data =  [];
        for ($i = 1; $i <= 10; $i++) {
            array_push($data, [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => bcrypt('123456'),
                'created_at' => $faker->dateTime(),
                'updated_at' =>  $faker->dateTime(),
            ]);
        }
        User::insert($data);
    }
}