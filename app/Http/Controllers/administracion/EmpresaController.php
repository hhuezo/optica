<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use App\Models\administracion\Empresa;
use App\Models\catalogo\Estado;
use App\Models\catalogo\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::get();

        $estados = Estado::where('type', 'ALL')->get();
        $sectores = Sector::get();

        return view('administracion.empresa.index', compact('empresas', 'estados', 'sectores'));
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'nit' => 'nullable|string|max:50',
            'contact' => 'required|string|max:60',
            'phone' => 'required|string|max:10',
            'address' => 'required|string|max:150',
            'company_category_id' => 'required|exists:company_category,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 50 caracteres.',

            'nit.string' => 'El NIT debe ser una cadena de texto.',
            'nit.max' => 'El NIT no debe superar los 50 caracteres.',
            'nit.unique' => 'Ya existe una empresa registrada con este NIT.',

            'contact.required' => 'El contacto es obligatorio.',
            'contact.string' => 'El contacto debe ser una cadena de texto.',
            'contact.max' => 'El contacto no debe superar los 60 caracteres.',

            'phone.required' => 'El teléfono es obligatorio.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no debe superar los 10 caracteres.',

            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no debe superar los 150 caracteres.',

            'company_category_id.required' => 'El sector es obligatorio.',
            'company_category_id.exists' => 'El sector seleccionado no es válido.',
        ]);

        try {
            $empresa = new Empresa();
            $empresa->name = $validated['name'];
            $empresa->nit = $validated['nit'];
            $empresa->contact = $validated['contact'];
            $empresa->phone = $validated['phone'];
            $empresa->address = $validated['address'];
            $empresa->company_category_id = $validated['company_category_id'];
            $empresa->statuses_id = 2;
            $empresa->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al guardar empresa: ' . $e->getMessage(), [
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
            'name' => 'required|string|max:50',
            'nit' => 'nullable|string|max:50',
            'contact' => 'required|string|max:60',
            'phone' => 'required|string|max:10',
            'address' => 'required|string|max:150',
            'company_category_id' => 'required|exists:company_category,id',
            'statuses_id' => 'required|exists:statuses,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe superar los 50 caracteres.',

            'nit.string' => 'El NIT debe ser una cadena de texto.',
            'nit.max' => 'El NIT no debe superar los 50 caracteres.',
            'nit.unique' => 'Ya existe una empresa registrada con este NIT.',

            'contact.required' => 'El contacto es obligatorio.',
            'contact.string' => 'El contacto debe ser una cadena de texto.',
            'contact.max' => 'El contacto no debe superar los 60 caracteres.',

            'phone.required' => 'El teléfono es obligatorio.',
            'phone.string' => 'El teléfono debe ser una cadena de texto.',
            'phone.max' => 'El teléfono no debe superar los 10 caracteres.',

            'address.required' => 'La dirección es obligatoria.',
            'address.string' => 'La dirección debe ser una cadena de texto.',
            'address.max' => 'La dirección no debe superar los 150 caracteres.',

            'company_category_id.required' => 'El sector es obligatorio.',
            'company_category_id.exists' => 'El sector seleccionado no es válido.',

            'statuses_id.required' => 'El estado es obligatorio.',
            'statuses_id.exists' => 'El estado seleccionado no es válido.',
        ]);

        try {
            $empresa = new Empresa();
            $empresa->name = $validated['name'];
            $empresa->nit = $validated['nit'];
            $empresa->contact = $validated['contact'];
            $empresa->phone = $validated['phone'];
            $empresa->address = $validated['address'];
            $empresa->company_category_id = $validated['company_category_id'];
            $empresa->statuses_id = $validated['statuses_id'];
            $empresa->save();

            return back()->with('success', 'El registro ha sido modificado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al modificar empresa: ' . $e->getMessage(), [
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
