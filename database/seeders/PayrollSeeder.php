<?php

namespace Database\Seeders;

use App\Models\IncomeParam;
use App\Models\DiscountParam;
use App\Models\TaxAndContributionParam;
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
            ['code' => '0105', 'concept' => 'Trabajo sobretiempo (H. extras 25%)'],
            ['code' => '0106', 'concept' => 'Trabajo sobretiempo (H. extras 35%)'],
            ['code' => '0107', 'concept' => 'Trabajo en feriado o día descando'],
            ['code' => '0114', 'concept' => 'Vacaciones truncas'],
            ['code' => '0118', 'concept' => 'Remuneración vacacional'],
            ['code' => '0121', 'concept' => 'Remuneración o jornal básico'],
            ['code' => '0122', 'concept' => 'Remuneración permanente'],

            ['code' => '0201', 'concept' => 'Asignación familiar'],
            ['code' => '0306', 'concept' => 'Bonificaciones regulares'],
            ['code' => '0902', 'concept' => 'Bono de productividad'],
            ['code' => '0904', 'concept' => 'Compensación por Tiempo de Servicios'],
            ['code' => '0907', 'concept' => 'Licencia de goce de haber'],
            ['code' => '0914', 'concept' => 'Refriger. que no sea aliment. princip'],
            ['code' => '0923', 'concept' => 'Ing. 4ta cat.considerados 5ta cat.'],
            ['code' => '0928', 'concept' => 'Devolución retención exceso de imp renta 5ta cat.'],
        ];
        IncomeParam::insert($incomeParams);
        // $discountParams = [
        //     ['code' => '0701', 'concept' => 'Adelanto'],
        //     ['code' => '0702', 'concept' => 'Cuota sindical'],
        //     ['code' => '0705', 'concept' => 'Inasistencias'],
        //     ['code' => '0706', 'concept' => 'Otros desc. no deduc. de base imponib.'],
        // ];
        // DiscountParam::insert($discountParams);
        // $taxAndContributions = [
        //     ['code' => '0601', 'concept' => 'Comisión AFP porcentual', 'type' => 'employee'],
        //     ['code' => '0602', 'concept' => 'Conafovicer', 'type' => 'employee'],
        //     ['code' => '0605', 'concept' => 'Renta quinta categoría retenciones', 'type' => 'employee'],
        //     ['code' => '0606', 'concept' => 'Prima de seguro AFP', 'type' => 'employee'],
        //     ['code' => '0608', 'concept' => 'SPP - aportación obligatoria', 'type' => 'employee'],
        //     ['code' => '0611', 'concept' => 'Otras aportaciones trab./pensionis.', 'type' => 'employee'],

        //     ['code' => '0801', 'concept' => 'SPP - aportación voluntaria.', 'type' => 'employer'],
        //     ['code' => '0803', 'concept' => 'Póliza de seguro - D. LEG 688', 'type' => 'employer'],
        //     ['code' => '0804', 'concept' => 'Essalud(regular CBSSP agrar/ac)trab', 'type' => 'employer'],
        //     ['code' => '0805', 'concept' => 'SCTR pensiones', 'type' => 'employer'],
        //     ['code' => '0809', 'concept' => 'Otras aportaciones cargo empleador', 'type' => 'employer'],
        //     ['code' => '0810', 'concept' => 'EPS seguro complementario de trab', 'type' => 'employer'],
        //     ['code' => '0813', 'concept' => 'ONP - SCTR', 'type' => 'employer'],
        //     ['code' => '0814', 'concept' => 'Compañía de seguro - SCTR', 'type' => 'employer'],
        // ];
        // TaxAndContributionParam::insert($taxAndContributions);
    }
}
