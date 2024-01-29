<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Permission::factory()->create(['name' => 'Crear']);
        \App\Models\Permission::factory()->create(['name' => 'Actualizar']);
        \App\Models\Permission::factory()->create(['name' => 'Eliminar']);
        \App\Models\Permission::factory()->create(['name' => 'Consultar']);
    }
}
