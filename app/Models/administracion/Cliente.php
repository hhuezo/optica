<?php

namespace App\Models\administracion;

use App\Models\catalogo\Estado;
use App\Models\ventas\Contrato;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clients';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'lastname',
        'identification',
        'birthdate',
        'phone',
        'address',
        'company_id',
        'statuses_id',
        'nit',
        'employee_code',
        'dependencia',
        'email',
        'reference_name',
        'reference_phone'
    ];

    protected $guarded = [];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'company_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }

    public function contratos()
    {
        return $this->hasMany(Contrato::class,'clients_id');
    }

}
