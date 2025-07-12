<?php

namespace App\Models\ventas;

use App\Models\administracion\Bodega;
use App\Models\administracion\Cliente;
use App\Models\catalogo\Estado;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contracts';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'number',
        'date',
        'term',
        'payment_day',
        'amount',
        'remaining',
        'created_at',
        'updated_at',
        'finished_at',
        'payment_type',
        'monthly_payment',
        'clients_id',
        'statuses_id',
        'warehouses_id',
        'advance',
        'diagnostic',
        'service_for',
        'observation'
    ];



    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'clients_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'statuses_id');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'warehouses_id');
    }

    public function detalles()
    {
        return $this->hasMany(ContratoDetalle::class, 'contracts_id');
    }

    public function abonos()
    {
        return $this->hasMany(Abono::class, 'contracts_id');
    }

}
