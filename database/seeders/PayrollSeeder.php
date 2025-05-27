<?php

namespace Database\Seeders;

use App\Models\IncomeParam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $incomeParams = [
            [ 'code' => '0121' , 'concept' => 'Remuneración o jornal básico' ],
            [ 'code' => '0122' , 'concept' => 'Remuneración permanente' ],
            [ 'code' => '0201' , 'concept' => 'Asignación familiar' ],
            [ 'code' => '0306' , 'concept' => 'Bonificaciones regulares' ],
            [ 'code' => '0902' , 'concept' => 'Bono de productividad' ],
            [ 'code' => '0907' , 'concept' => 'Licencia de goce de haber' ],
            [ 'code' => '0914' , 'concept' => 'Refriger. que no sea aliment. princip' ],
            [ 'code' => '0923' , 'concept' => 'Ing. 4ta cat.considerados 5ta cat.' ],
            [ 'code' => '0928' , 'concept' => 'Devolución retención exceso de imp renta 5ta cat.' ],
        ];
        IncomeParam::insert($incomeParams);
    }
}
