<?php

namespace App\Http\Controllers\catalogo;

use App\Http\Controllers\Controller;
use App\Models\catalogo\Estado;
use App\Models\catalogo\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarcaController extends Controller
{

    public function index()
    {
        $marcas = Marca::get();
        $estados = Estado::where('type', 'ALL')->get();

        return view('catalogo.marca.index', compact('marcas', 'estados'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate(
            [
                'name' => 'required|string|max:40',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no debe superar los 40 caracteres.',
            ]
        );

        try {
            $marca = new Marca();
            $marca->name = $validated['name'];
            $marca->statuses_id = 2;
            $marca->save();

            return back()->with('success', 'El registro ha sido creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar la marca: ' . $e->getMessage(), [
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

    public function edit(string $id)
    {
        //
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:40',
                'statuses_id' => 'required|exists:statuses,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de texto.',
                'name.max' => 'El nombre no debe superar los 40 caracteres.',
                'statuses_id.required' => 'El estado es obligatorio.',
                'statuses_id.exists' => 'El estado seleccionado no es válido.',
            ]
        );

        try {
            $marca = Marca::findOrFail($id);
            $marca->name = $validated['name'];
            $marca->statuses_id = $validated['statuses_id'];;
            $marca->save();

            return back()->with('success', 'El registro ha sido creado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar la marca: ' . $e->getMessage(), [
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
