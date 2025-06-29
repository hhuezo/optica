<?php

namespace App\Models\administracion;

use App\Models\catalogo\Estado;
use App\Models\catalogo\Marca;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'sku',
        'description',
        'cost',
        'price',
        'color',
        'model',
        'brands_id',
        'statuses_id',
        'track_inventory'
    ];

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'statuses_id');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'brands_id');
    }
}
