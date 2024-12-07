<?php

namespace App\Http\Controllers\ProjectArea;

use App\Models\Employee;
use App\Models\Preproject;
use App\Models\Service;

class ProjectConstants
{
    public function generateTemplate($data)
    {
        $template = null;
        if ($data['template'] === 'Mantenimiento') {
            $name = 'PINT OBRA MRD MANTENIMIENTO INTEGRAL REGION SUR ' . $this->formatDate($data['date']);
            $template = [
                'preproject' => [
                    'date'=>$data['date'],
                    'customer_id' => 1,
                    'cost_line_id'=>1,
                    'cost_center_id'=>$data['cost_center_id'],
                    'subcustomer_id' => null,
                    'description' => $name,
                    'title' => null,
                    'code' => $this->getCode($data['date'], 'CICSA-PINTOBRAM'),
                    'cpe' => $data['cpe'],
                    'status' => 1,

                ],
                'preproject_contacts' => $data['contacts'],
                'preproject_quote' => [
                    'name' => $name,
                    'date' => $data['date'],
                    'supervisor' => 'Alexander Azabache',
                    'boss' => 'Hosmer Castillo',
                    'deliverable_time' => $this->getDaysInMonth($data['date']),
                    'validity_time' => 5,
                    'rev' => 1,
                    'deliverable_place' => 'Zona Sur Peru',
                    'payment_type' => 'CREDITO',
                    'observations' => 'A 30 días después de entregada la factura',
                    'state' => true
                ],
                
                'quote_services' => $this->getQuoteServicesStructured($data['services']),
                'project' => [
                    'priority'=> 'Alta',
                    'description'=> $name,
                    'cost_line_id'=>1,
                    'cost_center_id'=>$data['cost_center_id'],
                    'status'=>null
                ],
                'project_employees' => $this->getEmployeesStructured($data['employees'], $data['date']) 


            ];
        }
        return $template;

    }




    public function getCode($date, $code)
    {
        $year = date('Y', strtotime($date));
        $totalYearProjects = Preproject::whereYear('date', $year)->count() + 1;
        $formattedTotal = str_pad($totalYearProjects, 3, '0', STR_PAD_LEFT);
        return $year . '-' . $formattedTotal . '-' . strtoupper($code);
    }



    private function formatDate($date) {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        $meses = [
            '01' => 'ENERO',
            '02' => 'FEBRERO',
            '03' => 'MARZO',
            '04' => 'ABRIL',
            '05' => 'MAYO',
            '06' => 'JUNIO',
            '07' => 'JULIO',
            '08' => 'AGOSTO',
            '09' => 'SEPTIEMBRE',
            '10' => 'OCTUBRE',
            '11' => 'NOVIEMBRE',
            '12' => 'DICIEMBRE'
        ];
        $mes = $meses[$dateTime->format('m')];
        $ano = $dateTime->format('Y');
        return "$mes $ano";
    }

    function getDaysInMonth($date) {
        $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
        if ($dateTime !== false) {
            $year = $dateTime->format('Y');
            $month = $dateTime->format('n');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            return $daysInMonth;
        } else {
            return 'Fecha no válida';
        }
    }


    function getQuoteServicesStructured ($services) {
        $result = [];
        foreach($services as $item){
            $result[$item['id']] = [
                'service_id' => $item['id'],
                'resource_entry_id' => null,
                'days' => $item['days'],
                'profit_margin'=> $item['profit_margin'], //variable
                'rent_price'=> $item['original_price'],
            ];
        }
        return $result;
    }

    function getEmployeesStructured ($employees, $date) {
        $result = [];
        $days = $this->getDaysInMonth($date);
        foreach($employees as $item){
            $emp = Employee::find($item['id']);
            $result[
                $item['id']] = [
                    'charge' => $item['charge'],
                    'salary_per_day' => $emp->salaryPerDay($days)
                ];
        }
        return $result;
    }

}