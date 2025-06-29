<?php

namespace App\Models\seguridad;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'name',

    ];

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions', 'role_id');
    }
}
