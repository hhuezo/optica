<?php

namespace App\Models\catalogo;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'statuses';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'description',
        'type'
    ];

    protected $guarded = [];
}
