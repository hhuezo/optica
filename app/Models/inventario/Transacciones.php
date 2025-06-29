<?php

namespace App\Models\inventario;

use App\Models\administracion\Bodega;
use App\Models\administracion\Producto;
use Illuminate\Database\Eloquent\Model;

class Transacciones extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'quantity',
        'products_id',
        'warehouses_id',
        'documents_id',
        'time_stamp'
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

    public function documento()
    {
        return $this->belongsTo(Documento::class,'documents_id');
    }
}
