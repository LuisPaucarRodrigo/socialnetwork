<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickMaterial extends Model
{
    use HasFactory;

    protected $table = 'quick_materials';

    protected $fillable = [
        'description',
        'unit',
    ];

    protected $appends = [
        'total_quantity',
        'available_quantity'
    ];

    public function quick_materials_entries ()
    {
        return $this->hasMany(QuickMaterialsEntry::class, 'quick_material_id');
    }

    public function getTotalQuantityAttribute ()
    {
        return $this->quick_materials_entries->sum('quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        $totalQuantity = $this->total_quantity; // Total de todas las entradas de material
        $outputQuantity = 0; // Inicializamos la cantidad de salida total en 0

        // Recorremos cada entrada de material y sumamos las cantidades de los outputs asociados
        foreach ($this->quick_materials_entries as $entry) {
            $outputQuantity += $entry->quick_materials_outputs->sum('output_quantity');
        }

        // Restamos la suma de los outputs al total de cantidad
        return $totalQuantity - $outputQuantity;
    }
}
