<?php

use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = 1;

        $faker = Factory::create();

        for ($j = 1; $j <= $users; $j++) {
            $rand = rand(0, 15);
            $year = rand(-1, 0);
            $today = Carbon::now();
            $created = $today->addDays($rand);

            $user = User::create([
                'name' => $faker->name,
                'email' => ($j == 1) ? "admin@admin.com" : $faker->email,
                'remember_token' => str_random(10),
                'organization_id' => 1,
                'password' => bcrypt('123456'),
                'created_at' => $created,
                'updated_at' => $created,
            ]);
        }

        // for( $i = 1; $i<=$users; $i++ ) {
        //     UserDetails::create([
        //        //...
        //     ]);
        // }
    }
}
