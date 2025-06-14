<?php

namespace Database\Seeders;

use App\Models\Functionality;
use App\Models\RoleFunctionality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

use Database\Seeders\Permissions\FunctionalityPermissionSeeder;
use Database\Seeders\Permissions\ModuleSeeder;
use Database\Seeders\Permissions\PermissionSeeder;

class AllPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Comienza transacción
        
        $roles = Role::with('functionalities')->get();
        
        
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            DB::table('role_functionalities')->delete();
            DB::statement('ALTER TABLE role_functionalities AUTO_INCREMENT = 1');
            
            DB::table('functionality_permissions')->delete();
            DB::statement('ALTER TABLE functionality_permissions AUTO_INCREMENT = 1');
            
            DB::table('permissions')->delete();
            DB::statement('ALTER TABLE permissions AUTO_INCREMENT = 1');
            
            DB::table('functionalities')->delete();
            DB::statement('ALTER TABLE functionalities AUTO_INCREMENT = 1');
            
            DB::table('modules')->delete();
            DB::statement('ALTER TABLE modules AUTO_INCREMENT = 1');
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            DB::beginTransaction();
            // Seeders hijos
            $this->call([
                ModuleSeeder::class,
                PermissionSeeder::class,
                FunctionalityPermissionSeeder::class,
            ]);
        
            // Restaurar funcionalidades a roles
            foreach ($roles as $rol) {
                foreach ($rol->functionalities as $func) {
                    $rg = Functionality::where('key_name', $func->key_name)->first();
                    if ($rg) {
                        RoleFunctionality::create([
                            'role_id' => $rol->id,
                            'functionality_id' => $rg->id,
                        ]);
                    }
                }
            }
        
            DB::commit();
            $this->command->info('✅ AllPermissionsSeeder completado correctamente.');
        
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('❌ Error en AllPermissionsSeeder: ' . $e->getMessage());
        }
        
    }
}
