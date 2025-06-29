<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use App\Models\administracion\Bodega;
use App\Models\administracion\Sucursal;
use App\Models\catalogo\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bodegas = Bodega::get();

        $estados = Estado::where('type', 'ALL')->get();
        $sucursales = Sucursal::where('statuses_id', 2)->get();

        return view('administracion.bodega.index', compact('bodegas', 'estados','sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 40 caracteres.',
        ]);

        try {
            $bodega = new Bodega();
            $bodega->name = $validated['name'];
            $bodega->store_id = $request->store_id ?? null;
            $bodega->statuses_id = 2;
            $bodega->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar bodega: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40',
            'statuses_id' => 'required|exists:statuses,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 40 caracteres.',
            'statuses_id.required' => 'El estado es obligatorio.',
            'statuses_id.exists' => 'El estado seleccionado no es válido.',
        ]);

        try {
            $bodega = Bodega::findOrFail($id);
            $bodega->name = $validated['name'];
            $bodega->store_id = $request->store_id ?? null;
            $bodega->statuses_id = $validated['statuses_id'];
            $bodega->save();

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar bodega: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
