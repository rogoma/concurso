<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //\App\Models\Concurso::factory()->count(20)->create();
        $this->call([
            //ChangeTypeCurrent::class,
        ]);
    }
}
