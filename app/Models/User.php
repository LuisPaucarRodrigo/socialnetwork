<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'dni',
        'platform',
        'password',
        'role_id',
        'area_id',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function employee(){
        return $this->hasOne(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function additionalCost()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function project_quoute()
    {
        return $this->hasMany(ProjectQuote::class);
    }

    public function hasPermission($permission)
    {
        $role = $this->role; // Obtener el único rol del usuario

        if ($role) { // Verificar si el usuario tiene un rol asignado
            return $role->permissions()->where('name', $permission)->exists();
        }

        return false; // Si el usuario no tiene un rol asignado, retorna falso
    }

    public function onePermission()
    {
        $role = $this->role; // Obtener el único rol del usuario

        if ($role) { // Verificar si el usuario tiene un rol asignado
            return $role->permissions()->pluck('name');
        }

        // No se encontraron permisos, retornar una colección vacía o null
        return collect(); // o return null;
    }

    public function preprojects()
    {
        return $this->belongsToMany(Preproject::class, 'preproject_user')->withTimestamps();
    }
}
