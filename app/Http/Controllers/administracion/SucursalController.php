<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use App\Models\administracion\Sucursal;
use App\Models\catalogo\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SucursalController extends Controller
{

    public function index()
    {
        $sucursales = Sucursal::get();

        $estados = Estado::where('type', 'ALL')->get();

        return view('administracion.sucursal.index', compact('sucursales', 'estados'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:150',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 50 caracteres.',

            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no debe superar los 150 caracteres.',
        ]);

        try {
            $sucursal = new Sucursal();
            $sucursal->name = $validated['name'];
            $sucursal->address = $validated['address'];
            $sucursal->statuses_id = 2;
            $sucursal->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar sucursal: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar el registro. Por favor intente nuevamente.');
        }
    }

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
            'name' => 'required|string|max:50',
            'address' => 'required|string|max:150',
            'statuses_id' => 'required|exists:statuses,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 50 caracteres.',

            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no debe superar los 150 caracteres.',

            'statuses_id.required' => 'El estado es obligatorio.',
            'statuses_id.exists' => 'El estado seleccionado no es válido.',
        ]);

        try {
            $sucursal = Sucursal::findOrFail($id);
            $sucursal->name = $validated['name'];
            $sucursal->address = $validated['address'];
            $sucursal->statuses_id =  $validated['statuses_id'];
            $sucursal->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar sucursal: ' . $e->getMessage(), [
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
