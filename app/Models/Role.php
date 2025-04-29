<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name','description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function functionalities()
    {
        return $this->belongsToMany(Functionality::class, 'role_functionalities');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
