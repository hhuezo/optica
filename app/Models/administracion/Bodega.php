<?php

namespace App\Models\administracion;

use App\Models\catalogo\Estado;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = 'warehouses';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'store_id',
        'statuses_id'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class,'store_id');
    }
}
