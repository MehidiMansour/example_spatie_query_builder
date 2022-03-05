<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DummyProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $data =  [];
        for ($i = 1; $i <= 10; $i++) {
            array_push($data, [
                'name' => $faker->name,
                'description' => $faker->text,
                'created_at' => $faker->dateTime(),
                'updated_at' =>  $faker->dateTime(),
            ]);
        }
        Project::insert($data);
        $data = [];
        $users = User::pluck('id') -> toArray();
        $projects = Project::pluck('id') -> toArray();
        for ($i = 1; $i <= 50; $i++) {
            array_push($data, [
                'user_id'          => $faker->randomElement($users),
                'project_id' => $faker->randomElement($projects),

            ]);
        }

        DB::table('project_user')->insert($data);
    }
}