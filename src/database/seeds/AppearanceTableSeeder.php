<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class AppearanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        ## House Cleaning
        \DB::table('appearance')->delete();

        factory(App\Appearance::class)->create([
            'key'   => 'color_primary',
            'value' => 'orange',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_secondary',
            'value' => '#333',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_links',
            'value' => 'blue',
            'type'  => 'CSS_VAR',
        ]);

        factory(App\Appearance::class)->create([
            'key'   => 'color_background',
            'value' => 'white',
            'type'  => 'CSS_VAR',
        ]);
    }
}