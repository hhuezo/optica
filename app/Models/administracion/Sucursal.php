<?php

namespace App\Models\administracion;

use App\Models\catalogo\Estado;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'stores';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'address',
        'statuses_id'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }
}
