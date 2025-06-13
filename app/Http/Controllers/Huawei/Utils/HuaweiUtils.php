<?php

namespace App\Http\Controllers\Huawei\Utils;

use App\Constants\HuaweiConstants;
use Carbon\Carbon;

class HuaweiUtils
{
    private static ?array $data = null;

    public static function getData(): array
    {
        if (self::$data === null) {
            self::$data = [
                'employees' => HuaweiConstants::getEmployees(),
                'static_expense_types' => HuaweiConstants::getStaticExpenseTypes(),
                'variable_expense_types' => HuaweiConstants::getVariableExpenseTypes(),
                'cdp_types' => HuaweiConstants::getCDPTypes(),
                'macro_projects' => HuaweiConstants::getMacroProjects(),
                'operators' => HuaweiConstants::getOperators(),
            ];
        }

        return self::$data;
    }

    public static function sanitizeDate($date)
    {
        $formats = [
            'd / m / Y',
            'd/m/Y',
            'Y-m-d',
            'd-m-Y',
            'd.m.Y',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }


    public static function sanitizeNumber($text)
    {
        $sanitized = preg_replace('/[^0-9.]/', '', $text);

        if (substr_count($sanitized, '.') > 1) {
            $parts = explode('.', $sanitized);
            $sanitized = implode('', array_slice($parts, 0, -1)) . '.' . end($parts);
        }

        return $sanitized;
    }

    public static function sanitizeText($text, $mode)
    {
        if ($mode) {
            return ucwords(strtolower($text));
        } else {
            $text = strtoupper($text);

            $text = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $text
            );

            return preg_replace('/\s+/', '', $text);
        }
    }

    public static function sanitizeText2($text)
    {
        return preg_replace('/^\(.*?\)\s*/', '', $text);
    }
}