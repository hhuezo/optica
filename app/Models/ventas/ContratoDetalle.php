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
        'right_eye_sphere',
        'right_eye_cylinder',
        'right_eye_axis',
        'right_eye_addition',
        'left_eye_sphere',
        'left_eye_cylinder',
        'left_eye_axis',
        'left_eye_addition',
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
