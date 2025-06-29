<?php

namespace App\Models\ventas;

use App\Models\administracion\Producto;
use Illuminate\Database\Eloquent\Model;

class ContratoDetalle extends Model
{
    protected $table = 'contract_details';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'quantity',
        'right_eye_graduation',
        'left_eye_graduation',
        'discount',
        'contracts_id',
        'products_id',
        'price',
    ];


    public function producto()
    {
        return $this->belongsTo(Producto::class, 'products_id');
    }



    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'contracts_id');
    }
}
