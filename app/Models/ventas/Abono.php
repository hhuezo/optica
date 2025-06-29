<?php

namespace App\Models\ventas;

use App\Models\administracion\Sucursal;
use Illuminate\Database\Eloquent\Model;

class Abono extends Model
{

    protected $table = 'receipts';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'number',
        'amount',
        'date',
        'contracts_id',
        'statuses_id',
    ];


    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contracts_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'statuses_id');
    }
}
