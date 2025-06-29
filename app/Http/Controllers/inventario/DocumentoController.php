<?php

namespace App\Http\Controllers\inventario;

use App\Http\Controllers\Controller;
use App\Models\administracion\Bodega;
use App\Models\administracion\Producto;
use App\Models\catalogo\Estado;
use App\Models\catalogo\TipoDocumento;
use App\Models\inventario\Documento;
use App\Models\inventario\DocumentoDetalle;
use App\Models\inventario\Stock;
use App\Models\inventario\Transacciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentos = Documento::orderBy('id', 'desc')->take(500)->get();
        $bodegas = Bodega::where('statuses_id', 2)->get();
        $estados = Estado::where('type', 'DOC')->get();
        $tipos_documento = TipoDocumento::where('statuses_id', 2)->get();
        return view('inventario.documento.index', compact('documentos', 'bodegas', 'tipos_documento', 'estados'));
    }


    public function data()
    {
        $documentos = DB::table('documents')
            ->select(
                'documents.id',
                'documents.doc_number',
                'documents.justification',
                'documents.warehouses_id',
                'documents.statuses_id',
                'document_types.name as document_types',
                'warehouses.name as warehouses',
                'statuses.description as statuses'
            )
            ->join('document_types', 'document_types.id', '=', 'documents.document_types_id')
            ->join('warehouses', 'warehouses.id', '=', 'documents.warehouses_id')
            ->join('statuses', 'statuses.id', '=', 'documents.statuses_id')
            ->orderBy('documents.id', 'desc');

        return DataTables::of($documentos)
            ->addColumn('actions', function ($item) {
                return '
                <button class="btn btn-sm btn-info btn-wave"
                    onclick="loadEdit('
                    . $item->id . ', \''
                    . e($item->doc_number) . '\', \''
                    . e($item->justification) . '\', '
                    . $item->warehouses_id . ', '
                    . $item->statuses_id . ')">
                    <i class="ri-edit-line"></i>
                </button>
                &nbsp;
                <a href="' . url('documento/' . $item->id) . '" class="btn btn-sm btn-success btn-wave">
                    <i class="ri-file-line"></i>
                </a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doc_number' => 'required|string|max:50',
            'justification' => 'required|string|max:300',
            'warehouses_id' => 'required|exists:warehouses,id',
            'document_types_id' => 'required|exists:document_types,id',
            'to_warehouse_id' => 'required_if:document_types_id,5',
        ], [
            'doc_number.required' => 'El número de documento es obligatorio.',
            'doc_number.max' => 'El número de documento no debe superar los 50 caracteres.',
            'justification.required' => 'La justificación es obligatoria.',
            'justification.max' => 'La justificación no debe superar los 300 caracteres.',
            'document_types_id.required' => 'Debe seleccionar un tipo de documento.',
            'document_types_id.exists' => 'El tipo de documento seleccionado no existe.',
            'warehouses_id.required' => 'Debe seleccionar una bodega.',
            'warehouses_id.exists' => 'La bodega seleccionada no existe.',
            'to_warehouse_id.required_if' => 'Debe seleccionar la bodega destino si el tipo de documento es traslado.',
        ]);

        // Si se mandó el campo, validar que exista
        $validator->sometimes('to_warehouse_id', 'exists:warehouses,id', function ($input) {
            return isset($input->to_warehouse_id);
        });

        // Ejecutar validación
        $validator->validate();

        // Validar que no sean iguales si es traslado
        if ($request->document_types_id == 5 && $request->warehouses_id == $request->to_warehouse_id) {
            return back()
                ->withInput()
                ->withErrors(['to_warehouse_id' => 'La bodega destino no puede ser igual a la bodega origen.']);
        }



        try {

            $documento = new Documento();
            $documento->doc_number = $request->doc_number;
            $documento->justification = $request->justification;
            $documento->created_at = now();
            $documento->applied_at = null;
            $documento->contract_id = null;
            $documento->document_types_id = $request->document_types_id;
            $documento->users_id = auth()->id();
            $documento->statuses_id = 7;
            $documento->warehouses_id = $request->warehouses_id;
            if ($request->document_types_id == 5) {
                $documento->to_warehouse_id = $request->to_warehouse_id;
            }
            $documento->save();

            if ($request->document_types_id == 1) {
                return redirect('documento/ingreso/' . $documento->id)->with('success', 'Documento registrado correctamente.');
            } else if ($request->document_types_id == 2) {
                return redirect('documento/salida/' . $documento->id)->with('success', 'Documento registrado correctamente.');
            } else if ($request->document_types_id == 3) {
                return redirect('documento/ajuste/' . $documento->id)->with('success', 'Documento registrado correctamente.');
            } else if ($request->document_types_id == 5) {
                return redirect('documento/traslado/' . $documento->id)->with('success', 'Documento registrado correctamente.');
            }
        } catch (\Exception $e) {
            Log::error('Error al guardar el documento y su detalle: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ocurrió un error al registrar el documento.');
        }
    }

    public function ingreso($id)
    {
        $documento = Documento::findOrFail($id);
        $productos = Producto::where('statuses_id', 2)->where('track_inventory', 1)->get();
        return view('inventario.documento.ingreso', compact('documento', 'productos'));
    }


    public function salida($id)
    {
        $documento = Documento::findOrFail($id);
        $productos = Producto::where('statuses_id', 2)->where('track_inventory', 1)->get();
        return view('inventario.documento.salida', compact('documento', 'productos'));
    }

    public function traslado($id)
    {
        $documento = Documento::findOrFail($id);
        $productos = Producto::where('statuses_id', 2)->where('track_inventory', 1)->get();
        return view('inventario.documento.traslado', compact('documento', 'productos'));
    }

    public function ajuste($id)
    {
        $documento = Documento::findOrFail($id);
        $productos = Producto::where('statuses_id', 2)->where('track_inventory', 1)->get();
        return view('inventario.documento.ajuste', compact('documento', 'productos'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $documento = Documento::findOrFail($id);
        $productos = Producto::where('statuses_id', 2)->where('track_inventory', 1)->get();


        if ($documento->document_types_id == 1) {
            return view('inventario.documento.ingreso', compact('documento', 'productos'));
        } else if ($documento->document_types_id == 2) {
            return view('inventario.documento.salida', compact('documento', 'productos'));
        } else if ($documento->document_types_id == 3) {
            return view('inventario.documento.ajuste', compact('documento', 'productos'));
        } else if ($documento->document_types_id == 5) {
            return view('inventario.documento.traslado', compact('documento', 'productos'));
        }
    }


    public function detalleStore(Request $request)
    {
        $request->validate([
            'documents_id' => 'required|exists:documents,id',
            'products_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
        ], [
            'documents_id.required' => 'Debe seleccionar un documento.',
            'documents_id.exists'   => 'El documento seleccionado no existe en la base de datos.',

            'products_id.required'  => 'Debe seleccionar un producto.',
            'products_id.exists'    => 'El producto seleccionado no existe en la base de datos.',

            'quantity.required'     => 'La cantidad es obligatoria.',
            'quantity.numeric'      => 'La cantidad debe ser un número válido.',
            'quantity.min'          => 'La cantidad debe ser al menos 1.',
        ]);



        try {

            $detalle = new DocumentoDetalle();
            $detalle->created_at = now();
            $detalle->quantity = $request->quantity;
            $detalle->documents_id = $request->documents_id;
            $detalle->products_id = $request->products_id;
            $detalle->save();

            return back()->with('success', 'producto registrado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar el  su detalle: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ocurrió un error al registrar el detalle.');
        }
    }



    public function detalleDestroy(string $id)
    {
        try {
            $detalle = DocumentoDetalle::findOrFail($id);
            $detalle->delete();

            return back()->with('success', 'producto eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar el  su detalle: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Ocurrió un error al eliminar el detalle.');
        }
    }





    public function procesarIngreso($id)
    {

        // Buscar el documento por su ID o lanzar error si no existe
        $documento = Documento::findOrFail($id);

        // Obtener el tipo de documento asociado (Ingreso, Salida, Ajuste, etc)
        $tipo_documento = TipoDocumento::findOrFail($documento->document_types_id);


        // Validar que el tipo de documento sea 'I' (Ingreso)
        if ($tipo_documento->type !== 'I') {
            throw ValidationException::withMessages([
                'tipo_documento' => ["Solo se soportan documentos de tipo 'Ingreso'."]
            ]);
        }

        // Validar que el documento tenga al menos un detalle
        if ($documento->detalles->isEmpty()) {
            throw ValidationException::withMessages([
                'detalles' => ["El documento no contiene productos para procesar."]
            ]);
        }

        // Iniciar una transacción para que todos los cambios se hagan atómicamente
        DB::beginTransaction();

        try {

            // Obtener el ID de la bodega relacionada al documento
            $bodega_id = $documento->warehouses_id;



            // Recorrer cada detalle del documento (productos y cantidades)
            foreach ($documento->detalles as $detalle) {
                // Validar que cada detalle tenga producto y cantidad
                if (!$detalle->products_id || !$detalle->quantity) {
                    throw new \Exception("Detalle de documento con datos incompletos: producto o cantidad faltante.");
                }

                // Buscar el stock existente para el producto y bodega, o crear uno nuevo si no existe
                $stok = Stock::firstOrNew([
                    'products_id' => $detalle->products_id,
                    'warehouses_id' => $bodega_id
                ]);

                // Actualizar la cantidad sumando la cantidad del detalle al stock actual (o 0 si no existía)
                $stok->quantity = ($stok->exists ? $stok->quantity : 0) + $detalle->quantity;
                $stok->save(); // Guardar el stock actualizado

                // Registrar la transacción de ingreso
                $transaccion = new Transacciones();
                $transaccion->quantity = $detalle->quantity; // Cantidad ingresada (positiva)
                $transaccion->products_id = $detalle->products_id;
                $transaccion->warehouses_id = $bodega_id;
                $transaccion->documents_id = $documento->id;
                $transaccion->time_stamp = now(); // Fecha y hora actual
                $transaccion->save(); // Guardar transacción
            }

            // Actualizar el estado del documento como procesado (status 8)
            $documento->statuses_id  = 8;

            // Registrar la fecha y hora en que se aplicó el documento
            $documento->applied_at  = now();

            $documento->save(); // Guardar cambios en el documento

            DB::commit(); // Confirmar la transacción (hacer permanentes los cambios)

            return redirect('documento/')->with('success', 'Documento procesado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir todos los cambios si hubo error

            // Guardar el error en el log para diagnóstico
            Log::error('Error al procesar el documento: ' . $e->getMessage(), [
                'document_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);

            // Volver atrás con mensaje de error
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function procesarTraslado($id)
    {
        // Buscar el documento por su ID o lanzar error si no existe
        $documento = Documento::findOrFail($id);

        // Obtener el tipo de documento asociado (Ingreso, Salida, Ajuste, etc)
        /* $tipo_documento = TipoDocumento::findOrFail($documento->document_types_id);*/

        // Validar que el tipo de documento sea traslado
        if ($documento->document_types_id != 5) {
            throw ValidationException::withMessages([
                'tipo_documento' => ["Solo se soportan documentos de tipo 'Traslado'."]
            ]);
        }

        // Validar que el documento tenga al menos un detalle
        if ($documento->detalles->isEmpty()) {
            throw ValidationException::withMessages([
                'detalles' => ["El documento no contiene productos para procesar."]
            ]);
        }

        // Validar stock antes de iniciar la transacción
        $errores = [];

        foreach ($documento->detalles as $detalle) {
            $producto = $detalle->producto->sku ?? "ID {$detalle->products_id}";

            $stok = Stock::where('products_id', $detalle->products_id)
                ->where('warehouses_id', $documento->warehouses_id)
                ->first();

            if (!$stok) {
                $errores["producto_{$detalle->products_id}"] = "No hay stock del producto '{$producto}' en la bodega de origen.";
            } elseif ($detalle->quantity > $stok->quantity) {
                $errores["producto_{$detalle->products_id}"] = "Stock insuficiente del producto '{$producto}' (Disponible: {$stok->quantity}, Requerido: {$detalle->quantity}).";
            }
        }

        if (!empty($errores)) {
            throw ValidationException::withMessages($errores);
        }


        // Si no hay errores, ahora sí se inicia la transacción
        DB::beginTransaction();

        $bodega_id = $documento->warehouses_id; // Bodega origen
        $bodega_destino_id = $documento->to_warehouse_id; // Bodega destino

        foreach ($documento->detalles as $detalle) {
            if (!$detalle->products_id || !$detalle->quantity) {
                throw new \Exception("Detalle de documento con datos incompletos: producto o cantidad faltante.");
            }

            // 1. Restar stock de bodega origen
            $stockOrigen = Stock::where([
                'products_id' => $detalle->products_id,
                'warehouses_id' => $bodega_id
            ])->first();

            if (!$stockOrigen || $stockOrigen->quantity < $detalle->quantity) {
                $producto = $detalle->producto->description ?? "ID {$detalle->products_id}";
                throw new \Exception("Stock insuficiente para el producto '{$producto}' en la bodega origen.");
            }

            $stockOrigen->quantity -= $detalle->quantity;
            $stockOrigen->save();

            // Registrar transacción de salida en bodega origen
            $transOut = new Transacciones();
            $transOut->quantity = -1 * $detalle->quantity;
            $transOut->products_id = $detalle->products_id;
            $transOut->warehouses_id = $bodega_id;
            $transOut->documents_id = $documento->id;
            $transOut->time_stamp = now();
            $transOut->save();

            // 2. Sumar stock a bodega destino
            $stockDestino = Stock::firstOrNew([
                'products_id' => $detalle->products_id,
                'warehouses_id' => $bodega_destino_id
            ]);

            $stockDestino->quantity = ($stockDestino->exists ? $stockDestino->quantity : 0) + $detalle->quantity;
            $stockDestino->save();

            // Registrar transacción de entrada en bodega destino
            $transIn = new Transacciones();
            $transIn->quantity = $detalle->quantity;
            $transIn->products_id = $detalle->products_id;
            $transIn->warehouses_id = $bodega_destino_id;
            $transIn->documents_id = $documento->id;
            $transIn->time_stamp = now();
            $transIn->save();
        }

        // Actualizar el estado del documento como procesado (status 8)
        $documento->statuses_id  = 8;

        // Registrar la fecha y hora en que se aplicó el documento
        $documento->applied_at  = now();

        $documento->save(); // Guardar cambios en el documento*/

        DB::commit(); // Confirmar la transacción (hacer permanentes los cambios)

        return redirect('documento/')->with('success', 'Documento procesado correctamente.');
        /*} catch (\Exception $e) {
            DB::rollBack(); // Revertir todos los cambios si hubo error

            // Guardar el error en el log para diagnóstico
            Log::error('Error al procesar el documento: ' . $e->getMessage(), [
                'document_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);

            // Volver atrás con mensaje de error
            return back()->with('error', 'Error: ' . $e->getMessage());
        }*/
    }


    public function procesarSalida($id)
    {
        // Buscar el documento por su ID
        $documento = Documento::findOrFail($id);


        if ($documento->document_types_id != 2) {
            throw ValidationException::withMessages([
                'tipo_documento' => ["Solo se soportan documentos de tipo 'Salida'."]
            ]);
        }

        // Validar que el documento tenga detalles
        if ($documento->detalles->isEmpty()) {
            throw ValidationException::withMessages([
                'detalles' => ["El documento no contiene productos para procesar."]
            ]);
        }

        $bodega_id = $documento->warehouses_id;

        // Validar stock antes de procesar
        $errores = [];
        foreach ($documento->detalles as $detalle) {
            $producto = $detalle->producto->sku ?? "ID {$detalle->products_id}";
            $stok = Stock::where('products_id', $detalle->products_id)
                ->where('warehouses_id', $bodega_id)
                ->first();

            if (!$stok) {
                $errores["producto_{$detalle->products_id}"] = "No hay stock del producto '{$producto}' en la bodega de origen.";
            } elseif ($detalle->quantity > $stok->quantity) {
                $errores["producto_{$detalle->products_id}"] = "Stock insuficiente del producto '{$producto}' (Disponible: {$stok->quantity}, Requerido: {$detalle->quantity}).";
            }
        }

        if (!empty($errores)) {
            throw ValidationException::withMessages($errores);
        }

        // Iniciar transacción
        DB::beginTransaction();

        try {
            foreach ($documento->detalles as $detalle) {
                $stok = Stock::where([
                    'products_id' => $detalle->products_id,
                    'warehouses_id' => $bodega_id
                ])->first();

                // Restar el stock
                $stok->quantity -= $detalle->quantity;
                $stok->save();

                // Registrar transacción
                $transaccion = new Transacciones();
                $transaccion->quantity = -1 * $detalle->quantity;
                $transaccion->products_id = $detalle->products_id;
                $transaccion->warehouses_id = $bodega_id;
                $transaccion->documents_id = $documento->id;
                $transaccion->time_stamp = now();
                $transaccion->save();
            }

            // Marcar documento como procesado
            $documento->statuses_id = 8;
            $documento->applied_at = now();
            $documento->save();

            DB::commit();

            return redirect('documento/')->with('success', 'Documento de salida procesado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al procesar documento de salida: ' . $e->getMessage(), [
                'document_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function procesarAjuste($id)
    {
        $documento = Documento::findOrFail($id);
        $tipo_documento = TipoDocumento::findOrFail($documento->document_types_id);

        // Validar que el tipo de documento sea 'A' (Ajuste)
        if ($documento->document_types_id != 3) {
            throw ValidationException::withMessages([
                'tipo_documento' => ["Solo se soportan documentos de tipo 'Ajuste'."]
            ]);
        }

        // Validar que el documento tenga al menos un detalle
        if ($documento->detalles->isEmpty()) {
            throw ValidationException::withMessages([
                'detalles' => ["El documento no contiene productos para procesar."]
            ]);
        }

        DB::beginTransaction();

        try {
            $bodega_id = $documento->warehouses_id;

            foreach ($documento->detalles as $detalle) {
                if (!$detalle->products_id || !$detalle->quantity) {
                    throw new \Exception("Detalle de documento con datos incompletos: producto o cantidad faltante.");
                }

                $stok = Stock::firstOrNew([
                    'products_id' => $detalle->products_id,
                    'warehouses_id' => $bodega_id
                ]);

                $stok->quantity = ($stok->exists ? $stok->quantity : 0) + $detalle->quantity;
                $stok->save();

                $transaccion = new Transacciones();
                $transaccion->quantity = $detalle->quantity;
                $transaccion->products_id = $detalle->products_id;
                $transaccion->warehouses_id = $bodega_id;
                $transaccion->documents_id = $documento->id;
                $transaccion->time_stamp = now();
                $transaccion->save();
            }

            $documento->statuses_id = 8;
            $documento->applied_at = now();
            $documento->save();

            DB::commit();

            return redirect('documento/')->with('success', 'Ajuste procesado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al procesar el ajuste: ' . $e->getMessage(), [
                'document_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }



    /*

public function procesar(string $id)
    {
        dd("procesar");
        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($id);
            $tipo_documento = TipoDocumento::findOrFail($documento->document_types_id);
            $bodega_id = $documento->warehouses_id;

            if (!in_array($tipo_documento->type, ['I', 'S', 'A'])) {
                throw new \Exception("Tipo de documento '{$tipo_documento->type}' no soportado.");
            }

            foreach ($documento->detalles as $detalle) {
                if (!$detalle->products_id || !$detalle->quantity) {
                    throw new \Exception("Detalle de documento con datos incompletos: producto o cantidad faltante.");
                }

                $producto = $detalle->producto->description ?? "ID {$detalle->products_id}";
                $bodega = $documento->bodega->name ?? "ID {$bodega_id}";

                if ($tipo_documento->type === "I") {
                    $stok = Stock::firstOrNew([
                        'products_id' => $detalle->products_id,
                        'warehouses_id' => $bodega_id
                    ]);

                    $stok->quantity = ($stok->exists ? $stok->quantity : 0) + $detalle->quantity;
                    $stok->save();

                    $transaccion = new Transacciones();
                    $transaccion->quantity = $detalle->quantity;
                    $transaccion->products_id = $detalle->products_id;
                    $transaccion->warehouses_id = $bodega_id;
                    $transaccion->documents_id = $documento->id;
                    $transaccion->time_stamp = now();
                    $transaccion->save();
                } elseif ($tipo_documento->type === "S") {
                    $stok = Stock::where('products_id', $detalle->products_id)
                        ->where('warehouses_id', $bodega_id)
                        ->first();

                    if (!$stok) {
                        throw new \Exception("No hay stock para el producto {$producto} en la bodega {$bodega}.");
                    }

                    if ($stok->quantity < $detalle->quantity) {
                        throw new \Exception("Stock insuficiente para el producto {$producto} en la bodega {$bodega} (disponible: {$stok->quantity}, solicitado: {$detalle->quantity}).");
                    }

                    $stok->quantity -= $detalle->quantity;
                    $stok->save();

                    $transaccion = new Transacciones();
                    $transaccion->quantity = -1 * $detalle->quantity;
                    $transaccion->products_id = $detalle->products_id;
                    $transaccion->warehouses_id = $bodega_id;
                    $transaccion->documents_id = $documento->id;
                    $transaccion->time_stamp = now();
                    $transaccion->save();
                } elseif ($tipo_documento->type === "A") {
                    $stok = Stock::firstOrNew([
                        'products_id' => $detalle->products_id,
                        'warehouses_id' => $bodega_id
                    ]);

                    $stok->quantity = ($stok->exists ? $stok->quantity : 0) + $detalle->quantity;
                    $stok->save();

                    $transaccion = new Transacciones();
                    $transaccion->quantity = $detalle->quantity;
                    $transaccion->products_id = $detalle->products_id;
                    $transaccion->warehouses_id = $bodega_id;
                    $transaccion->documents_id = $documento->id;
                    $transaccion->time_stamp = now();
                    $transaccion->save();
                }
            }

            $documento->statuses_id  = 8;
            $documento->applied_at  = now();
            $documento->save();

            DB::commit();
            return redirect('documento')->with('success', 'Documento procesado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al procesar el documento: ' . $e->getMessage(), [
                'document_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    */



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'doc_number' => 'required|string|max:50',
            'justification' => 'required|string|max:300',
            'warehouses_id' => 'required|exists:warehouses,id',
            'statuses_id' => 'required|exists:statuses,id',
        ], [
            'doc_number.required' => 'El número de documento es obligatorio.',
            'justification.required' => 'La justificación es obligatoria.',
            'warehouses_id.required' => 'Debe seleccionar una bodega.',
            'statuses_id.required' => 'Debe seleccionar un estado.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $documento = Documento::findOrFail($id);
            $documento->doc_number = $request->doc_number;
            $documento->justification = $request->justification;
            $documento->statuses_id = $request->statuses_id;
            $documento->warehouses_id = $request->warehouses_id;
            $documento->save();

            return response()->json([
                'success' => true,
                'message' => 'Documento modificado correctamente.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el documento: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al modificar el documento.'
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
