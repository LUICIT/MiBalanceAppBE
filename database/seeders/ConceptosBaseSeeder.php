<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptosBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Solo si no existen (evitar duplicar)
        $base = [
            ['user_id'=> null, 'name'=>'Sueldo', 'type_period'=>'percepcion', 'created_at'=>now(), 'updated_at'=>now(), 'version'=>1],
            ['user_id'=> null, 'name'=>'ISR',    'type_period'=>'deduccion',  'created_at'=>now(), 'updated_at'=>now(), 'version'=>1],
            ['user_id'=> null, 'name'=>'IMSS',   'type_period'=>'deduccion',  'created_at'=>now(), 'updated_at'=>now(), 'version'=>1],
            ['user_id'=> null, 'name'=>'Bono',   'type_period'=>'percepcion', 'created_at'=>now(), 'updated_at'=>now(), 'version'=>1],
        ];

        foreach ($base as $row) {
            $exists = DB::table('concepts')
                ->whereNull('user_id')
                ->where('name', $row['name'])
                ->exists();
            if (!$exists) {
                DB::table('concepts')->insert($row);
            }
        }
    }
}
