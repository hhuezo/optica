<?php

namespace App\Models\inventario;

use App\Models\administracion\Producto;
use Illuminate\Database\Eloquent\Model;

class DocumentoDetalle extends Model
{
    protected $table = 'document_details';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'quantity',
        'documents_id',
        'products_id',
    ];

    protected $guarded = [];


    public function documento()
    {
        return $this->belongsTo(Documento::class,'documents_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class,'products_id');
    }
}
