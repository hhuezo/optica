<?php

namespace App\Models\inventario;

use App\Models\administracion\Bodega;
use App\Models\catalogo\Estado;
use App\Models\catalogo\TipoDocumento;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documents';

    protected $primaryKey = 'id';

    public $timestamps = false;


    protected $fillable = [
        'doc_number',
        'justification',
        'contract_id',
        'applied_at',
        'document_types_id',
        'warehouses_id',
        'statuses_id',
        'users_id',
        'created_at'
    ];

    protected $guarded = [];

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'statuses_id');
    }

    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'warehouses_id');
    }

    public function bodegaDestino()
    {
        return $this->belongsTo(Bodega::class, 'to_warehouse_id');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'document_types_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function detalles()
    {
        return $this->hasMany(DocumentoDetalle::class, 'documents_id');
    }
}
