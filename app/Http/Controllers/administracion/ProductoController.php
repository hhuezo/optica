<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use App\Models\administracion\Producto;
use App\Models\catalogo\Estado;
use App\Models\catalogo\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductoController extends Controller
{

    public function index()
    {
        $productos = Producto::get();

        $estados = Estado::where('type', 'ALL')->get();
        $marcas = Marca::where('statuses_id', 2)->get();

        return view('administracion.producto.index', compact('productos', 'estados', 'marcas'));
    }


    public function create()
    {
        //
    }



    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'sku' => 'required|string|max:30',
                'description' => 'required|string|max:50',
                'cost' => 'nullable|numeric|min:0',
                //'price' => 'required|numeric|min:0',
                'color' => 'nullable|string|max:50',
                'model' => 'nullable|string|max:50',
                'brands_id' => 'required|exists:brands,id',
                'track_inventory' => 'required|boolean',
            ],
            [
                'sku.required' => 'El SKU es obligatorio.',
                'sku.max' => 'El SKU no debe superar los 30 caracteres.',
                'sku.unique' => 'Este SKU ya está registrado.',
                'description.required' => 'La descripción es obligatoria.',
                'description.max' => 'La descripción no debe superar los 50 caracteres.',
                'cost.numeric' => 'El costo debe ser un número.',
                'cost.min' => 'El costo no puede ser negativo.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio no puede ser negativo.',
                'color.max' => 'El color no debe superar los 50 caracteres.',
                'model.max' => 'El modelo no debe superar los 50 caracteres.',
                'brands_id.required' => 'Debe seleccionar una marca.',
                'brands_id.exists' => 'La marca seleccionada no existe.',
                'track_inventory.required' => 'Debe indicar si se registra inventario.',
                'track_inventory.boolean' => 'El valor de inventario debe ser SI o NO.',
            ]
        );

        try {
            $producto = new Producto();
            $producto->sku = $validated['sku'];
            $producto->description = $validated['description'];
            $producto->cost = $validated['cost'] ?? 0;
            $producto->price = 0.00;
            $producto->color = $validated['color'] ?? null;
            $producto->model = $validated['model'] ?? null;
            $producto->brands_id = $validated['brands_id'];
            $producto->statuses_id = 2;
            $producto->track_inventory = $validated['track_inventory'];
            $producto->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al crear cliente: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar el cliente. Intente nuevamente.');
        }
    }


    public function show(string $id)
    {
        try {
            $producto = Producto::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'producto no encontrado.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'sku' => 'required|string|max:30',
                'description' => 'required|string|max:50',
                'cost' => 'nullable|numeric|min:0',
                //'price' => 'required|numeric|min:0',
                'color' => 'nullable|string|max:50',
                'model' => 'nullable|string|max:50',
                'brands_id' => 'required|exists:brands,id',
                'track_inventory' => 'required|boolean',
                'statuses_id' => 'required|exists:statuses,id',
            ],
            [
                'sku.required' => 'El SKU es obligatorio.',
                'sku.max' => 'El SKU no debe superar los 30 caracteres.',
                'sku.unique' => 'Este SKU ya está registrado.',
                'description.required' => 'La descripción es obligatoria.',
                'description.max' => 'La descripción no debe superar los 50 caracteres.',
                'cost.numeric' => 'El costo debe ser un número.',
                'cost.min' => 'El costo no puede ser negativo.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio no puede ser negativo.',
                'color.max' => 'El color no debe superar los 50 caracteres.',
                'model.max' => 'El modelo no debe superar los 50 caracteres.',
                'brands_id.required' => 'Debe seleccionar una marca.',
                'brands_id.exists' => 'La marca seleccionada no existe.',
                'track_inventory.required' => 'Debe indicar si se registra inventario.',
                'track_inventory.boolean' => 'El valor de inventario debe ser SI o NO.',
                'statuses_id.required' => 'El estado es obligatorio.',
                'statuses_id.exists' => 'El estado seleccionado no es válido.',
            ]
        );

        try {
            $producto = Producto::findOrFail($id);
            $producto->sku = $validated['sku'];
            $producto->description = $validated['description'];
            $producto->cost = $validated['cost'] ?? 0;
            //$producto->price = 0.00;
            $producto->color = $validated['color'] ?? null;
            $producto->model = $validated['model'] ?? null;
            $producto->brands_id = $validated['brands_id'];
            $producto->statuses_id = 2;
            $producto->track_inventory = $validated['track_inventory'];
            $producto->save();

            return back()->with('success', 'El registro ha sido creado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al crear producto: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Ocurrió un error al guardar el producto. Intente nuevamente.');
        }
    }

    public function destroy(string $id)
    {
        //
    }

    public function get_productos(string $id)
    {
        try {
            $productos = DB::table('products')
                ->select([
                    'products.id',
                    DB::raw("CONCAT(sku, ' - ', description, ' - ', color) as text"),
                    'brands.name as brands_name',
                    'stock.quantity as stock'
                ])
                ->join('brands', 'brands.id', '=', 'products.brands_id')
                ->join('stock', 'stock.products_id', '=', 'products.id')
                ->where('products.statuses_id', 2)
                ->whereNotNull('stock.quantity')
                ->where('stock.quantity', '>', 0)
                ->where('warehouses_id', $id)
                ->unionAll(
                    DB::table('products')
                        ->select([
                            'products.id',
                            DB::raw("CONCAT(sku, ' - ', description, ' - ', color) as text"),
                            'brands.name as brands_name',
                            DB::raw("'-' as stock")
                        ])
                        ->join('brands', 'brands.id', '=', 'products.brands_id')
                        ->where('products.track_inventory', 0)
                )
                ->get();

            return response()->json([
                'success' => true,
                'data' => $productos
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener productos: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener los productos.'
            ], 500);
        }
    }
}
