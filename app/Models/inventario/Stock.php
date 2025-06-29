<?php

namespace App\Models\inventario;

use App\Models\administracion\Bodega;
use App\Models\administracion\Producto;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'quantity',
        'products_id',
        'warehouses_id',
    ];

    protected $guarded = [];

    public function producto()
    {
        return $this->belongsTo(Producto::class,'products_id');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class,'warehouses_id');
    }
}
