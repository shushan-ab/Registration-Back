<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];
        foreach ($items as $item) {
            Role::create($item);
        }
    }
}
