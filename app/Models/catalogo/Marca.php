<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'brands';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'statuses_id'
    ];

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }


}
