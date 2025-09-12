<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Site;
use App\Models\Block;
use App\Models\Apartment;
use App\Models\Resident;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    $site = Site::create(['name' => 'Deneme Sitesi', 'address' => 'İstanbul', 'total_blocks' => 2]);

    $block = Block::create(['site_id' => $site->id, 'name' => 'A Blok', 'total_apartments' => 10]);

    $apartment = Apartment::create(['block_id' => $block->id, 'number' => '1', 'floor' => 1]);

    Resident::create(['apartment_id' => $apartment->id, 'name' => 'Ahmet Yılmaz', 'email' => 'ahmet@example.com', 'type' => 'owner']);


    }
}
