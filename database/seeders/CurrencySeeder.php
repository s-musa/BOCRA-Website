<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        
        $this->currencies();
        
        Schema::enableForeignKeyConstraints();
    }
    
    public function currencies(): void
    {
        $sql = file_get_contents(storage_path('currencies.sql'));
        
        // Execute the SQL statements
        DB::unprepared($sql);
    }
}
