<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'company_category';

    protected $primaryKey = 'id';

    public $timestamps = false;


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
