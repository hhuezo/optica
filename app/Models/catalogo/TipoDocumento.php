<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    protected $table = 'document_types';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'type',
        'statuses_id'
    ];

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }
}
