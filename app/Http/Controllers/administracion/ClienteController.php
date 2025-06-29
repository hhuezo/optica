<?php

namespace App\Http\Controllers\administracion;

use App\Http\Controllers\Controller;
use App\Models\administracion\Bodega;
use App\Models\administracion\Cliente;
use App\Models\administracion\Empresa;
use App\Models\administracion\Producto;
use App\Models\catalogo\Estado;
use App\Models\inventario\Documento;
use App\Models\inventario\Transacciones;
use App\Models\User;
use App\Models\ventas\Abono;
use App\Models\ventas\Contrato;
use App\Models\ventas\ContratoDetalle;
use App\Models\ventas\ContratoEmpleado;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClienteController extends Controller
{

    public function index()
    {

        /* $clientes = Cliente::select(
            'clients.*',
            'statuses.description as status_name',
            DB::raw("IFNULL(company.name, '-') as company_name"),
            DB::raw("(SELECT GROUP_CONCAT(contracts.number SEPARATOR ', ')
                  FROM contracts
                  WHERE contracts.clients_id = clients.id) as contracts")
        )
            ->join('statuses', 'statuses.id', '=', 'clients.statuses_id')
            ->leftJoin('company', 'company.id', '=', 'clients.company_id')
            //->whereIn('clients.statuses_id', [1, 2])
            //->take(100)
            ->get();*/

        $estados = Estado::where('type', 'ALL')->get();
        $empresas = Empresa::where('statuses_id', 2)->get();

        return view('administracion.cliente.index', compact('estados', 'empresas'));
    }

    public function data()
    {
        $clientes = Cliente::select(
            'clients.id',
            'clients.name',
            'clients.lastname',
            DB::raw("company.name as company"),
            'clients.phone',
            DB::raw("statuses.description as statuses"),
            DB::raw("(
                SELECT GROUP_CONCAT(contracts.number SEPARATOR ', ')
                FROM contracts
                WHERE contracts.clients_id = clients.id
            ) as contracts")
        )
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->join('statuses', 'clients.statuses_id', '=', 'statuses.id');

        return DataTables::of($clientes)
            ->addColumn('actions', function ($row) {
                return '
            <button class="btn btn-sm btn-info btn-wave" data-bs-toggle="modal"
                data-bs-target="#modal-edit" onclick="getCliente(' . $row->id . ')">
                <i class="ri-edit-line"></i>
            </button>
            &nbsp;
            <a href="' . url('cliente/' . $row->id) . '">
                <button class="btn btn-sm btn-warning btn-wave">
                    <i class="bi bi-card-list"></i>
                </button>
            </a>';
            })
            ->filterColumn('contracts', function ($query, $keyword) {
                // Subconsulta personalizada para búsqueda
                $query->whereRaw("(
            SELECT GROUP_CONCAT(contracts.number SEPARATOR ', ')
            FROM contracts
            WHERE contracts.clients_id = clients.id
        ) LIKE ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:50',
                'lastname' => 'required|string|max:50',
                'identification' => 'required|string|max:20',
                'phone' => 'required|string|max:50',
                'address' => 'required|string|max:100',
                'company_id' => 'required|exists:company,id',
                'nit' => 'nullable|string|max:50',
                'employee_code' => 'nullable|string|max:50',
                'dependencia' => 'nullable|string|max:255',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.max' => 'El nombre no debe superar los 50 caracteres.',
                'lastname.required' => 'El apellido es obligatorio.',
                'lastname.max' => 'El apellido no debe superar los 50 caracteres.',
                'identification.required' => 'El DUI es obligatorio.',
                'identification.max' => 'El DUI no debe superar los 20 caracteres.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no debe superar los 50 caracteres.',
                'address.required' => 'La dirección es obligatoria.',
                'address.max' => 'La dirección no debe superar los 100 caracteres.',
                'company_id.required' => 'Debe seleccionar una empresa.',
                'company_id.exists' => 'La empresa seleccionada no existe.',
                'nit.max' => 'El NIT no debe superar los 50 caracteres.',
                'employee_code.max' => 'El código de empleado no debe superar los 50 caracteres.',
                'dependencia.max' => 'La dependencia no debe superar los 255 caracteres.',
            ]
        );

        try {
            $cliente = new Cliente();
            $cliente->name = $validated['name'];
            $cliente->lastname = $validated['lastname'];
            $cliente->identification = $validated['identification'];
            $cliente->phone = $validated['phone'];
            $cliente->address = $validated['address'];
            $cliente->company_id = $validated['company_id'];
            $cliente->statuses_id = 2;
            $cliente->nit = $validated['nit'] ?? null;
            $cliente->employee_code = $validated['employee_code'] ?? null;
            $cliente->dependencia = $validated['dependencia'] ?? null;
            $cliente->save();

            return back()->with('success', 'El cliente ha sido creado correctamente.');
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
        $cliente = Cliente::findOrFail($id);
        $bodegas = Bodega::where('statuses_id', 2)->get();
        return view('administracion.cliente.show', compact('cliente', 'bodegas'));
    }


    public function get_cliente(string $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cliente no encontrado.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function edit(string $id, Request $request) {}

    public function update_record(Request $request, string $id)
    {

        // Validación de los datos del formulario
        // Validación manual
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'identification' => 'required|string|max:20',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:100',
            'company_id' => 'required|exists:company,id',
            'nit' => 'nullable|string|max:50',
            'employee_code' => 'nullable|string|max:50',
            'dependencia' => 'nullable|string|max:255',
            'statuses_id' => 'required|exists:statuses,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no debe superar los 50 caracteres.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.max' => 'El apellido no debe superar los 50 caracteres.',
            'identification.required' => 'El DUI es obligatorio.',
            'identification.max' => 'El DUI no debe superar los 20 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.max' => 'El teléfono no debe superar los 50 caracteres.',
            'address.required' => 'La dirección es obligatoria.',
            'address.max' => 'La dirección no debe superar los 100 caracteres.',
            'company_id.required' => 'Debe seleccionar una empresa.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'nit.max' => 'El NIT no debe superar los 50 caracteres.',
            'employee_code.max' => 'El código de empleado no debe superar los 50 caracteres.',
            'dependencia.max' => 'La dependencia no debe superar los 255 caracteres.',
            'statuses_id.required' => 'El estado es obligatorio.',
            'statuses_id.exists' => 'El estado seleccionado no es válido.',
        ]);

        // Aquí está el bloque que preguntabas:
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Intentar obtener el cliente y actualizarlo
            $cliente = Cliente::findOrFail($id);
            $cliente->name = $request->name;
            $cliente->lastname = $request->lastname;;
            $cliente->identification = $request->identification;
            $cliente->phone = $request->phone;
            $cliente->address = $request->address;
            $cliente->company_id = $request->company_id;
            $cliente->statuses_id = $request->statuses_id;
            $cliente->nit = $request->nit ?? null;
            $cliente->employee_code = $request->employee_code ?? null;
            $cliente->dependencia = $request->dependencia ?? null;
            $cliente->save();

            $data = Cliente::select(
                'clients.*',
                'statuses.description as status_name',
                DB::raw("IFNULL(company.name, '-') as company_name"),
                DB::raw("(SELECT GROUP_CONCAT(contracts.number SEPARATOR ', ')
                      FROM contracts
                      WHERE contracts.clients_id = clients.id) as contracts")
            )
                ->join('statuses', 'statuses.id', '=', 'clients.statuses_id')
                ->leftJoin('company', 'company.id', '=', 'clients.company_id')
                ->where('clients.id', $id)
                ->first();

            // Retornar respuesta JSON en caso de éxito
            return response()->json([
                'success' => true,
                'message' => 'El cliente ha sido modificado correctamente.',
                'cliente' => $data,
            ], 200);
        } catch (\Exception $e) {
            // Log de error
            Log::error('Error al modificar cliente: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            // Retornar respuesta JSON en caso de error
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Ocurrió un error al guardar el cliente. Intente nuevamente.'
            ], 500);
        }
    }




    public function destroy(string $id)
    {
        //
    }

    public function contrato_create(string $id)
    {
        $cliente = Cliente::findOrFail($id);
        $bodegas = Bodega::where('statuses_id', 2)->get();

        return view('administracion.cliente.contrato_create', compact('cliente', 'bodegas'));
    }

    public function contrato_store(string $id, Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|string|max:255|unique:contracts,number',
            'date' => 'required|date',
            'payment_type' => 'required|in:CONTADO,CREDITO',
            'warehouses_id' => 'required|exists:warehouses,id',
            'term' => 'required|integer|min:1',
            /*'products_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'right_eye_graduation' => 'required|string|max:255',
            'left_eye_graduation' => 'required|string|max:255',*/
        ], [
            'number.required' => 'El campo número es obligatorio.',
            'number.unique' => 'El valor del campo número ya está en uso.',
            'date.required' => 'La fecha es obligatoria.',
            'payment_type.required' => 'Debe seleccionar un tipo de pago.',
            'payment_type.in' => 'El tipo de pago seleccionado no es válido.',
            'warehouses_id.required' => 'Debe seleccionar una bodega.',
            'warehouses_id.exists' => 'La bodega seleccionada no existe.',
            'term.required' => 'El campo plazo es obligatorio.',
            'term.integer' => 'El plazo debe ser un número entero.',
            'term.min' => 'El plazo debe ser al menos 1.',
            'products_id.required' => 'Debe seleccionar un producto.',
            'products_id.exists' => 'El producto seleccionado no existe.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.numeric' => 'La cantidad debe ser un número.',
            'quantity.min' => 'La cantidad mínima es 1.',
            'discount.required' => 'El descuento es obligatorio.',
            'discount.numeric' => 'El descuento debe ser un número.',
            'discount.min' => 'El descuento no puede ser menor a 0.',
            'discount.max' => 'El descuento no puede ser mayor a 100.',
            'right_eye_graduation.required' => 'El campo graduación ojo derecho es obligatorio.',
            'left_eye_graduation.required' => 'El campo graduación ojo izquierdo es obligatorio.',
        ]);


        //try {
        $contrato = new Contrato();
        $contrato->number = $validated['number'];
        $contrato->date = $validated['date'];
        $contrato->term = $validated['term'];
        $contrato->payment_type = $validated['payment_type'];
        $contrato->clients_id = $id;
        $contrato->warehouses_id = $validated['warehouses_id'];

        $montoTotal = 0.00;
        $contrato->amount = $montoTotal;
        $contrato->remaining = $montoTotal;
        $contrato->advance = 0;
        $contrato->monthly_payment = $validated['payment_type'] === 'CREDITO'
            ? round($montoTotal / max($validated['term'], 1), 2)
            : 0;
        $contrato->statuses_id = 4;
        $contrato->save();

        /*$detalle = new ContratoDetalle();
            $detalle->contracts_id = $contrato->id;
            $detalle->products_id = $validated['products_id'];
            $detalle->quantity = $validated['quantity'];
            $detalle->price = $validated['price'];
            $detalle->discount = $validated['discount'] ?? 0.00;
            $detalle->right_eye_graduation = $validated['right_eye_graduation'];
            $detalle->left_eye_graduation = $validated['left_eye_graduation'];
            $detalle->save();*/


        return redirect()->route('cliente.contrato.show', $contrato->id)->with('success', 'Contrato agregado correctamente.');
        /*} catch (\Throwable $e) {

            // Opcional: log del error
            Log::error('Error al guardar contrato: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error al guardar el contrato. Intente nuevamente.');
        }*/
    }

    public function contrato_detalle_store(string $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contracts_id' => 'required|integer|exists:contracts,id',
            //'number' => 'required|string|max:255',
            //'date' => 'required|date',
            //'payment_type' => 'required|in:CONTADO,CREDITO',
            //'warehouses_id' => 'required|exists:warehouses,id',
            //'term' => 'required|integer|min:1',
            'products_id' => 'required|exists:products,id',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:1',
            //'discount' => 'required|numeric|min:0|max:100',
            'right_eye_graduation' => 'required|string|max:255',
            'left_eye_graduation' => 'required|string|max:255',
        ], [
            'number.required' => 'El campo número es obligatorio.',
            'number.unique' => 'El valor del campo número ya está en uso.',
            'date.required' => 'La fecha es obligatoria.',
            'payment_type.required' => 'Debe seleccionar un tipo de pago.',
            'payment_type.in' => 'El tipo de pago seleccionado no es válido.',
            'warehouses_id.required' => 'Debe seleccionar una bodega.',
            'warehouses_id.exists' => 'La bodega seleccionada no existe.',
            'term.required' => 'El campo plazo es obligatorio.',
            'term.integer' => 'El plazo debe ser un número entero.',
            'term.min' => 'El plazo debe ser al menos 1.',
            'products_id.required' => 'Debe seleccionar un producto.',
            'products_id.exists' => 'El producto seleccionado no existe.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio debe ser mayor a 0.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.numeric' => 'La cantidad debe ser un número.',
            'quantity.min' => 'La cantidad mínima es 1.',
            'discount.required' => 'El descuento es obligatorio.',
            'discount.numeric' => 'El descuento debe ser un número.',
            'discount.min' => 'El descuento no puede ser menor a 0.',
            'discount.max' => 'El descuento no puede ser mayor a 100.',
            'right_eye_graduation.required' => 'El campo graduación ojo derecho es obligatorio.',
            'left_eye_graduation.required' => 'El campo graduación ojo izquierdo es obligatorio.',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // ✅ Recuperar los datos validados manualmente
        $validated = $validator->validated();

        DB::beginTransaction();

        try {

            $detalle = new ContratoDetalle();
            $detalle->contracts_id = $request->contracts_id;
            $detalle->products_id = $validated['products_id'];
            $detalle->quantity = $validated['quantity'];
            $detalle->price = $validated['price'];
            $detalle->discount = $request->discount ?? 0.00;
            $detalle->right_eye_graduation = $validated['right_eye_graduation'];
            $detalle->left_eye_graduation = $validated['left_eye_graduation'];
            $detalle->save();

            DB::commit();

            return redirect()->route('cliente.contrato.show', $request->contracts_id)->with('success', 'Contrato agregado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Opcional: log del error
            Log::error('Error al guardar contrato: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error al guardar el contrato. Intente nuevamente.');
        }
    }

    public function contrato_detalle_delete(string $id)
    {
        try {
            $detalle = ContratoDetalle::findOrFail($id);
            $detalle->delete();

            return back()->with('success', 'Detalle del contrato eliminado correctamente.');
        } catch (\Throwable $e) {
            // Opcional: deshacer transacción si aplica
            DB::rollBack();

            // Registrar el error
            Log::error('Error al eliminar detalle del contrato: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el detalle del contrato. Intente nuevamente.');
        }
    }




    public function validar_contrato_store(string $id, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'number' => 'required|string|max:255|unique:contracts,number',
                'date' => 'required|date',
                'payment_type' => 'required|in:CONTADO,CREDITO',
                'warehouses_id' => 'required|exists:warehouses,id',
                'term' => 'required|integer|min:1',
                'products_id' => 'required|exists:products,id',
                'price' => 'required|numeric|min:0.01',
                'quantity' => 'required|numeric|min:1',
                'discount' => 'required|numeric|min:0|max:100',
                'right_eye_graduation' => 'required|string|max:255',
                'left_eye_graduation' => 'required|string|max:255',
            ], [
                'number.required' => 'El campo número es obligatorio.',
                'number.unique' => 'El valor del campo número ya está en uso.',
                'date.required' => 'La fecha es obligatoria.',
                'payment_type.required' => 'Debe seleccionar un tipo de pago.',
                'payment_type.in' => 'El tipo de pago seleccionado no es válido.',
                'warehouses_id.required' => 'Debe seleccionar una bodega.',
                'warehouses_id.exists' => 'La bodega seleccionada no existe.',
                'term.required' => 'El campo plazo es obligatorio.',
                'term.integer' => 'El plazo debe ser un número entero.',
                'term.min' => 'El plazo debe ser al menos 1.',
                'products_id.required' => 'Debe seleccionar un producto.',
                'products_id.exists' => 'El producto seleccionado no existe.',
                'price.required' => 'El precio es obligatorio.',
                'price.numeric' => 'El precio debe ser un número.',
                'price.min' => 'El precio debe ser mayor a 0.',
                'quantity.required' => 'La cantidad es obligatoria.',
                'quantity.numeric' => 'La cantidad debe ser un número.',
                'quantity.min' => 'La cantidad mínima es 1.',
                'discount.required' => 'El descuento es obligatorio.',
                'discount.numeric' => 'El descuento debe ser un número.',
                'discount.min' => 'El descuento no puede ser menor a 0.',
                'discount.max' => 'El descuento no puede ser mayor a 100.',
                'right_eye_graduation.required' => 'El campo graduación ojo derecho es obligatorio.',
                'left_eye_graduation.required' => 'El campo graduación ojo izquierdo es obligatorio.',
            ]);

            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }
    }


    public function contrato_show(Request $request, string $id)
    {

        $tab = $request->tab ?? 1;
        $contrato = Contrato::findOrFail($id);
        $bodegas = Bodega::where('statuses_id', 2)->get();

        $usuariosAsignados = ContratoEmpleado::where('contracts_id', $contrato->id)->pluck('users_id')->toArray();

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
            ->where('warehouses_id', $contrato->warehouses_id)
            ->unionAll(
                DB::table('products')
                    ->select([
                        'products.id',
                        DB::raw("CONCAT(sku, ' - ', description, ' - ', color) as text"),
                        'brands.name as brands_name',
                        DB::raw("'-' as stock")
                    ])
                    ->join('brands', 'brands.id', '=', 'products.brands_id')
                //->where('products.track_inventory', 0)
            )
            ->get();

        $abonos = $contrato->abonos->sum('amount') ?? 0.00;

        $users = User::where('id', '>', 1)->get();
        return view('administracion.cliente.contrato_show', compact('contrato', 'tab', 'bodegas', 'productos', 'users', 'usuariosAsignados','abonos'));
    }

    public function contrato_empleado_store(Request $request, string $id)
    {
        try {
            $userId = $request->empleado_id;

            if (!$userId) {
                return response()->json(['success' => false, 'message' => 'Empleado no especificado'], 400);
            }

            // Verificar si ya existe el registro
            $registro = ContratoEmpleado::where('users_id', $userId)
                ->where('contracts_id', $id)
                ->first();

            if ($registro) {
                // Eliminar si ya existe
                $registro->delete();
                return response()->json(['success' => true, 'action' => 'deleted']);
            } else {
                // Crear si no existe
                $registro = new ContratoEmpleado();
                $registro->users_id = $userId;
                $registro->contracts_id = $id;
                $registro->save();
                return response()->json(['success' => true, 'action' => 'created']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function processContract(Request $request, $contractId)
    {

        $userId = auth()->user()->id;

        // 1. Actualizar monto del contrato
        $this->updateContractAmount($contractId);

        // 2. Verificar stock insuficiente
        $errores = DB::table('contract_details')
            ->join('contracts', 'contracts.id', '=', 'contract_details.contracts_id')
            ->join('products', 'products.id', '=', 'contract_details.products_id')
            ->leftJoin('stock', function ($join) {
                $join->on('stock.products_id', '=', 'contract_details.products_id')
                    ->on('stock.warehouses_id', '=', 'contracts.warehouses_id');
            })
            ->select(
                'contracts.id',
                'contracts.warehouses_id',
                'contract_details.products_id',
                'products.sku',
                'contract_details.quantity',
                DB::raw('IFNULL(stock.quantity, 0) as stock')
            )
            ->whereColumn('contract_details.quantity', '>', DB::raw('IFNULL(stock.quantity, 0)'))
            ->where('products.track_inventory', 1)
            ->where('contracts.id', $contractId)
            ->get();

        foreach ($errores as $error) {
            $mensajesError[] = "La existencia del producto " . $error->sku . " es: " . $error->stock;
        }


        if ($errores->isNotEmpty()) {
            return back()->withErrors([
                'stock' => $mensajesError,
            ])->withInput();
        }

        // 3. Iniciar transacción
        DB::beginTransaction();

        try {
            // Crear contrato
            $contract = Contrato::findOrFail($contractId);
            $contract->monthly_payment    = $request->monthly_payment;
            $contract->advance    = $request->advance;
            $contract->save();
            //dd($contract);


            // 4. Crear el documento
            $documento = new Documento();
            $documento->doc_number = 'C-' . $contract->number;
            $documento->justification = 'Venta';
            $documento->contract_id = $contract->id;
            $documento->applied_at = now();
            $documento->document_types_id = 2;
            $documento->warehouses_id = $contract->warehouses_id;
            $documento->statuses_id = 7;
            $documento->users_id = $userId;
            $documento->created_at = now();
            $documento->save();


            // Insertar detalles del documento
            $details = DB::table('contract_details')
                ->join('products', 'products.id', '=', 'contract_details.products_id')
                ->where('contract_details.contracts_id', $contractId)
                ->where('products.track_inventory', 1)
                ->get();

            foreach ($details as $detail) {
                DB::table('document_details')->insert([
                    'quantity'      => $detail->quantity * -1,
                    'created_at'    => now(),
                    'documents_id'  => $documento->id,
                    'products_id'   => $detail->products_id,
                ]);
            }

            // Aplicar documento (actualizar stock)
            $items = DB::table('document_details')
                ->join('documents', 'document_details.documents_id', '=', 'documents.id')
                ->where('document_details.documents_id', $documento->id)
                ->select('document_details.id', 'document_details.quantity', 'document_details.products_id', 'documents.warehouses_id')
                ->get();


            foreach ($items as $item) {
                $stock = DB::table('stock')
                    ->where('products_id', $item->products_id)
                    ->where('warehouses_id', $item->warehouses_id)
                    ->first();

                if ($stock) {

                    DB::table('stock')->where('id', $stock->id)
                        ->update([
                            'quantity'   => $stock->quantity + $item->quantity,
                            'updated_at' => now(),
                        ]);
                }
            }

            // Obtener los datos necesarios
            $detalles = DB::table('document_details')
                ->join('documents', 'document_details.documents_id', '=', 'documents.id')
                ->where('document_details.documents_id', $documento->id)
                ->select(
                    'document_details.quantity',
                    'document_details.documents_id',
                    'document_details.products_id',
                    'documents.warehouses_id'
                )
                ->get();



            // Insertar manualmente con instancias
            foreach ($detalles as $detalle) {
                $transaccion = new Transacciones();
                $transaccion->quantity = $detalle->quantity;
                $transaccion->documents_id = $detalle->documents_id;
                $transaccion->products_id = $detalle->products_id;
                $transaccion->warehouses_id = $detalle->warehouses_id;
                $transaccion->time_stamp = now(); // o date('Y-m-d H:i:s')
                $transaccion->save();
            }

            // Cambiar estado del documento
            DB::table('documents')->where('id', $documento->id)->update([
                'statuses_id' => 8,
                'applied_at'  => now(),
            ]);

            // Cambiar estado del contrato
            DB::table('contracts')->where('id', $contractId)->update([
                'statuses_id' => 10,
            ]);

            DB::commit();

            return back()->with('success', 'Venta procesada con éxito');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al modificar empresa: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with('error', 'Ocurrió un error al procesar el registro. Por favor intente nuevamente.');
        }
    }


    private function updateContractAmount($contractId)
    {
        // Calcular el total del contrato
        $total = DB::table('contract_details')
            ->where('contracts_id', $contractId)
            ->selectRaw('IFNULL(SUM(quantity * price - ((quantity * price) * (discount / 100))), 0) as total')
            ->value('total');

        // Obtener el anticipo (advance)
        $advance = DB::table('contracts')
            ->where('id', $contractId)
            ->value('advance');

        // Actualizar el contrato con el monto total y restante
        DB::table('contracts')
            ->where('id', $contractId)
            ->update([
                'amount'    => $total,
                'remaining' => $total - $advance,
            ]);
    }


    public function contrato_abono(Request $request, $contractId)
    {
        // Validaciones con mensajes personalizados
        $request->validate([
            'number' => 'required|string|max:255|unique:receipts,number',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ], [
            'number.required' => 'El número del abono es obligatorio.',
            'number.string' => 'El número del abono debe ser un texto.',
            'number.max' => 'El número del abono no debe exceder los 255 caracteres.',
            'number.unique' => 'Ya existe un abono con este número.',

            'amount.required' => 'El monto del abono es obligatorio.',
            'amount.numeric' => 'El monto del abono debe ser un número.',
            'amount.min' => 'El monto debe ser mayor que cero.',

            'date.required' => 'La fecha del abono es obligatoria.',
            'date.date' => 'La fecha ingresada no es válida.',
        ]);

        try {
            DB::beginTransaction();

            // Crear el abono manualmente
            $abono = new Abono();
            $abono->number = $request->number;
            $abono->amount = $request->amount;
            $abono->date = $request->date;
            $abono->contracts_id = $contractId;
            $abono->statuses_id = 8;
            $abono->created_at = Carbon::now();
            $abono->updated_at = Carbon::now();
            $abono->save();

            // Actualizar el contrato
            $contrato = Contrato::findOrFail($contractId);
            $contrato->remaining -= $abono->amount;
            $contrato->save();

            // Cambiar estado si quedó en cero
            if ($contrato->remaining <= 0) {
                $contrato->statuses_id = 5;
                $contrato->save();
            }

            DB::commit();
            return redirect('cliente/contrato_show/' . $contractId . '?tab=3')->with('success', 'Abono registrado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Guardar el error en el log
            Log::error('Error al registrar abono en contrato', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect('cliente/contrato_show/' . $contractId . '?tab=3')->with('error', 'Abono registrado no se registro.');
        }
    }

    /*





public function process_contract($user, $contract_id)
	{
		$this->update_contract_amount($contract_id);

		$this->db->select("contracts.id, contracts.warehouses_id, contract_details.products_id, contract_details.quantity, IFNULL(stock.quantity, 0) `stock`")
			->from('contract_details')
			->join('contracts', 'contracts.id = contract_details.contracts_id', 'inner')
			->join('products', 'products.id = contract_details.products_id', 'inner')
			->join('stock', 'stock.products_id = contract_details.products_id AND stock.warehouses_id = contracts.warehouses_id', 'left')
			->where('contract_details.quantity > IFNULL(stock.quantity, 0)')
			->where('products.track_inventory', 1)
			->where('contracts.id', $contract_id);

		$error = $this->db->get()->row();
		if (isset($error)) {
			return array('error' => true, 'message' => 'No hay suficiente producto en la bodega seleccionada');
		}

		$this->db->trans_start();
		// creating the document for the sale
		$this->db->query("INSERT INTO documents
                        SELECT null, CONCAT('C-', contracts.number), 'Venta', NOW(), NULL, contracts.id, 2, ?, 7, contracts.warehouses_id
                        FROM contracts
                        WHERE contracts.id = ?", array($user, $contract_id));

		// adding the document detail
		$document_id = $this->db->select("MAX(id) last_id")->get('documents')->row()->last_id;
		$this->db->query("INSERT INTO document_details
                        SELECT null, abs(contract_details.quantity) * -1, NOW(), ?, contract_details.products_id
                        FROM contract_details
                        INNER JOIN products ON products.id = contract_details.products_id
                        WHERE contracts_id = ? AND products.track_inventory = 1", array($document_id, $contract_id));
		// applying the document
		// begin --------------------------------------------------
		// document detail
		$result = $this->db->select('document_details.quantity, document_details.products_id, documents.warehouses_id')
			->from('document_details')
			->join('documents', 'document_details.documents_id = documents.id', 'inner')
			->where('document_details.documents_id', $document_id)
			->get()->result();

		// checking: insert or update
		foreach ($result as $item) {
			$record = $this->db->select('id')
				->from('stock')
				->where('products_id',   $item->products_id)
				->where('warehouses_id', $item->warehouses_id)
				->get()->row();

			$this->db->query("UPDATE stock SET quantity = quantity + ?, updated_at = NOW() WHERE id = ?", array($item->quantity, $record->id));
		}
		// inserting transaction
		$this->db->query("INSERT INTO transactions
                        SELECT	null, document_details.quantity, NOW(), document_details.documents_id, document_details.products_id, documents.warehouses_id
                        FROM	document_details
                        INNER JOIN documents ON document_details.documents_id = documents.id
                        WHERE document_details.documents_id = ?", $document_id);
		// changing document status
		$this->db->query('UPDATE documents SET statuses_id = 8, applied_at = NOW() WHERE id = ?', $document_id);
		// finish --------------------------------------------------

		$this->db->where('id', $contract_id)
			->update('contracts', array('statuses_id' => 10));

		$this->db->trans_complete();

		return array('error' => false, 'message' => 'Venta procesada con exito');
	}



	applyReceipt() {
			this.new_receipt.date = $('#receipt_date').val();
			if (this.new_receipt.number == undefined || this.new_receipt.number == "") {
				swal("Error", "Por favor introduzca el NUMERO del recibo", "error");
				return;
			}

			if (this.new_receipt.amount == undefined || this.new_receipt.amount == "" || parseFloat(this.new_receipt.amount) === 0) {
				swal("Error", "Por favor especifique el MONTO del recibo", "error");
				return;
			}

			if (this.new_receipt.date == undefined || this.new_receipt.date == "") {
				swal("Error", "Por favor especifique la FECHA del recibo", "error");
				return;
			}

			if (parseFloat(this.new_receipt.amount) > this.new_item.remaining) {
				swal("Error", "El monto supera el saldo de la deuda", "error");
				return;
			}

			this.new_receipt.contract_id = this.new_item.id;
			this.$http.post(base_url + 'sales/apply_receipt', this.new_receipt).then(function (response) {
				this.new_receipt.number = "";
				this.new_item = response.body;
				if (parseInt(this.new_item.remaining) == 0) {
					$('#modalReceipt').modal('hide');
					swal("Información", "El contrato ha sido pagado en su totalidad.", "info");
					this.getAll();
				} else {
					this.new_item.amount_dollar = '$' + parseFloat(this.new_item.amount).toFixed(2);
					this.new_item.remaining_dollar = '$' + parseInt(this.new_item.remaining).toFixed(2);
					this.new_item.amount = parseFloat(this.new_item.amount).toFixed(2);
					this.new_item.remaining = parseFloat(this.new_item.remaining).toFixed(2);
					this.new_item.monthly_payment = parseFloat(this.new_item.monthly_payment).toFixed(2);

					this.getRecipts();
				}
			}, function () {
				swal("Error", "Ocurrio un error al aplicar el nuevo recibo. Por favor verifique que el número de recibo no se repite.", "error");
			});
		},




	public function apply_receipts($data)
	{
		$this->db->trans_start();
		$this->db->insert('receipts', $data);
		$params = array($data['amount'], $data['contracts_id']);
		$this->db->query('UPDATE contracts SET remaining = remaining - ? WHERE id = ?', $params);

		$contract = $this->db->where('id', $data['contracts_id'])->get('contracts')->row();
		if ($contract->remaining == 0) {
			$this->db->query('UPDATE contracts SET statuses_id = 5 WHERE id = ?', $data['contracts_id']);
		}

		$this->db->trans_complete();
		return $contract;
	}

    */
}
