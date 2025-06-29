<?php

namespace App\Models\ventas;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ContratoEmpleado extends Model
{
    protected $table = 'contract_employees';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'users_id',
        'contracts_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'clients_id');
    }

     public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contracts_id');
    }
}
