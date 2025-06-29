<?php

namespace App\Models\administracion;

use App\Models\catalogo\Estado;
use App\Models\catalogo\Sector;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    protected $table = 'company';

    protected $primaryKey = 'id';

    //public $timestamps = false;


    protected $fillable = [
        'name',
        'phone',
        'nit',
        'address',
        'contact',
        'company_category_id',
        'statuses_id'
    ];

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class,'statuses_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class,'company_category_id');
    }
}
