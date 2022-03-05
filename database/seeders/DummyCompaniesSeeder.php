<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummyCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();



        $data = [];

        $users = User::pluck('id')->toArray();
        for ($i = 1; $i <= 50; $i++) {
            array_push($data, [
                'name' => $faker->sentence(),
                'user_id' =>  $faker->randomElement($users),
                'created_at' => $faker->dateTime(),
                'updated_at' =>  $faker->dateTime(),
            ]);
        }

        Company::insert($data);
    }
}