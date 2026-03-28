<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        $this->countries();
        
        Schema::enableForeignKeyConstraints();
    }
    
    public function countries(): void
    {
        $json = File::get(database_path('countries.json'));
        $data = json_decode($json, true);
        
        foreach ($data as $country) {
            DB::table('countries')->insert([
                'name' => $country['name'],
                'nationality' => $country['nationality'],
                'iso2' => $country['iso2'],
                'iso3' => $country['iso3'],
                'phone_code' => $country['phone_code'],
                'currency' => $country['currency'],
                'currency_name' => $country['currency_name'],
                'currency_symbol' => $country['currency_symbol'],
                'region' => $country['region'],
                'sub_region' => $country['subregion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
