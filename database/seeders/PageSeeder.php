<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create([
            'name' => 'dashboard'
        ]);

        Page::create([
            'name' => 'admin/dashboard'
        ]);

        Page::create([
            'name' => 'superadmin/dashboard'
        ]);

        Page::create([
            'name' => 'approver/dashboard'
        ]);
    }
}
