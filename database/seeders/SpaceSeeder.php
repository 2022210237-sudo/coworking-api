<?php

namespace Database\Seeders;

use App\Models\Space;
use App\Models\SpaceType;
use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    public function run(): void
    {
        $sala = SpaceType::firstOrCreate(['name' => 'Sala']);
        $escritorio = SpaceType::firstOrCreate(['name' => 'Escritorio']);
        $cabina = SpaceType::firstOrCreate(['name' => 'Cabina']);

        $spaces = [
            ['name' => 'Sala de Reuniones A', 'space_type_id' => $sala->id, 'floor' => 1, 'capacity' => 10, 'price_per_hour' => 50.00],
            ['name' => 'Sala de Reuniones B', 'space_type_id' => $sala->id, 'floor' => 2, 'capacity' => 6, 'price_per_hour' => 35.00],
            ['name' => 'Escritorio Flexible 1', 'space_type_id' => $escritorio->id, 'floor' => 1, 'capacity' => 1, 'price_per_hour' => 8.00],
            ['name' => 'Escritorio Flexible 2', 'space_type_id' => $escritorio->id, 'floor' => 1, 'capacity' => 1, 'price_per_hour' => 8.00],
            ['name' => 'Cabina Privada 1', 'space_type_id' => $cabina->id, 'floor' => 2, 'capacity' => 2, 'price_per_hour' => 20.00],
            ['name' => 'Cabina Privada 2', 'space_type_id' => $cabina->id, 'floor' => 2, 'capacity' => 2, 'price_per_hour' => 20.00],
        ];

        foreach ($spaces as $space) {
            Space::firstOrCreate(
                ['name' => $space['name']],
                $space + ['is_active' => true]
            );
        }
    }
}
