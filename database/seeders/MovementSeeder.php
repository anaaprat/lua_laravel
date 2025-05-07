<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movement;
use App\Models\User;
use App\Models\User as Bar;

class MovementSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $bars = Bar::where('role', 'bar')->get();

        foreach ($users as $user) {
            $bar = $bars->random();

            // Movimiento positivo: ingreso de crédito en un bar
            Movement::create([
                'user_id' => $user->id,
                'bar_id' => $bar->id,
                'amount' => rand(10, 50),
            ]);

            $bar = $bars->random();

            // Movimiento negativo: gasto en un bar
            Movement::create([
                'user_id' => $user->id,
                'bar_id' => $bar->id,
                'amount' => -rand(5, 30),
            ]);
        }
    }
}