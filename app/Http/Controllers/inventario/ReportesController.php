<?php

namespace App\Http\Controllers\inventario;

use App\Http\Controllers\Controller;
use App\Models\administracion\Bodega;
use App\Models\administracion\Empresa;
use App\Models\inventario\Stock;
use App\Models\seguridad\Role;
use App\Models\User;
use App\Models\ventas\Contrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function generales()
    {
        $salesByCategory = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.company_category_id',
                DB::raw('IFNULL(SUM(contracts.remaining), 0.00) as total_sales')
            )
            ->whereIn('contracts.statuses_id', [5, 10])
            ->where('company.company_category_id', 1)
            ->groupBy('company.company_category_id')
            ->first();

        $totalPublico = $salesByCategory->total_sales;

        $salesByCategory = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.company_category_id',
                DB::raw('IFNULL(SUM(contracts.remaining), 0.00) as total_sales')
            )
            ->whereIn('contracts.statuses_id', [5, 10])
            ->where('company.company_category_id', 2)
            ->groupBy('company.company_category_id')
            ->first();
        $totalPrivado = $salesByCategory->total_sales;

        $resultPublico = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.name',
                DB::raw('IFNULL(SUM(contracts.remaining), 0.00) as total')
            )
            ->where('contracts.payment_type', 'CREDITO')
            ->where('company.company_category_id', 1)
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->groupBy('company.name')
            ->get();

        $resultPrivado = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.name',
                DB::raw('IFNULL(SUM(contracts.remaining), 0.00) as total')
            )
            ->where('contracts.payment_type', 'CREDITO')
            ->where('company.company_category_id', 2)
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->groupBy('company.name')
            ->get();

        $empresas = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.id',
                'company.name as name',
                DB::raw('SUM(contracts.amount) as amount'),
                DB::raw('SUM(contracts.remaining) as remaining')
            )
            ->where('contracts.payment_type', 'CREDITO')
            // ->where('company.id', $companyId) // <- descomenta si lo necesitas
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->where('clients.statuses_id', '<>', 3)
            ->groupBy('company.id', 'company.name')
            ->get();

        $rolAsesor = Role::findOrFail(2);
        $asesores = $rolAsesor->users;


        return view('reportes.general', compact('totalPublico', 'totalPrivado', 'resultPublico', 'resultPrivado', 'empresas', 'asesores'));
    }

    public function pagos_mensuales(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fechaInicio ?? Carbon::now()->startOfMonth());
        $fechaFinal  = Carbon::parse($request->fechaFinal  ?? Carbon::now()->endOfMonth());



        $pagos = DB::table('receipts')
            ->join('contracts', 'receipts.contracts_id', '=', 'contracts.id')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereBetween('receipts.created_at', [$fechaInicio, $fechaFinal])
            ->select(
                'receipts.id',
                'receipts.created_at',
                'receipts.number as receipt_number',
                'receipts.amount',
                'contracts.number as contract_number',
                'contracts.amount as amount_contract',
                'contracts.advance',
                'contracts.remaining',
                DB::raw("CONCAT(clients.name, ' ', clients.lastname) as client"),
                'clients.dependencia',
                'company.name as company'
            )
            ->get();



        return view('reportes.pagos_mensuales', compact('pagos', 'fechaInicio', 'fechaFinal'));
    }



    public function sector($id, $exportar)
    {
        $result = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.name',
                DB::raw('IFNULL(SUM(contracts.remaining), 0.00) as total')
            )
            ->where('contracts.payment_type', 'CREDITO')
            ->where('company.company_category_id', $id)
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->groupBy('company.name')
            ->get();

        $sector = $id == 1 ? ' Sector Público' : ' Sector Privado';



        if ($exportar == 1) {
            $pdf = Pdf::loadView('reportes.sector', compact('result', 'sector'));
            return $pdf->download('reporte_sector.pdf');
        }

        return view('reportes.sector_excel', compact('result', 'sector'));
    }


    public function estado_cuenta_empresa($exportar)
    {
        $empresas = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                'company.name as name',
                DB::raw('SUM(contracts.amount) as amount'),
                DB::raw('SUM(contracts.remaining) as remaining')
            )
            ->where('contracts.payment_type', 'CREDITO')
            // ->where('company.id', $companyId) // <- descomenta si lo necesitas
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->where('clients.statuses_id', '<>', 3)
            ->groupBy('company.name')
            ->get();

        if ($exportar == 1) {
            $pdf = Pdf::loadView('reportes.estado_cuenta_empresa', compact('empresas'));
            return $pdf->download('estado_cuenta.pdf');
        }

        return view('reportes.estado_cuenta_empresa_excel', compact('empresas'));
    }

    public function estado_cuenta_por_empresa($id, $exportar)
    {
        $empresa = Empresa::findOrFail($id);
        $clientes = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->select(
                DB::raw("CONCAT(clients.name, ' ', clients.lastname) as name"),
                'clients.employee_code',
                DB::raw('SUM(contracts.amount) as amount'),
                DB::raw('SUM(contracts.remaining) as remaining')
            )
            ->where('contracts.payment_type', 'CREDITO')
            ->where('company.id', $id)
            ->where('contracts.remaining', '>', 0)
            ->where('contracts.statuses_id', '<>', 6)
            ->where('clients.statuses_id', '<>', 3)
            ->groupBy(DB::raw("CONCAT(clients.name, ' ', clients.lastname)"), 'clients.employee_code')
            ->get();


        if ($exportar == 1) {
            $pdf = Pdf::loadView('reportes.estado_cuenta_por_empresa', compact('clientes', 'empresa'));
            return $pdf->download('estado_cuenta.pdf');
        }

        return view('reportes.estado_cuenta_por_empresa_excel', compact('clientes', 'empresa'));
    }

    public function comisiones(Request $request, $id)
    {

        $fechaInicio = $request->fechaInicio;
        $fechaFinal = $request->fechaFinal;
        $exportar = $request->exportar ?? 1;

        $vendedor = User::findOrFail($id);

        $sales_percentage = $vendedor->sales_percentage ?? 2.00;
        $collection_percentage = $vendedor->collection_percentage ?? 4.00;

        $ventas = DB::table('contracts')
            ->join('contract_employees', 'contracts.id', '=', 'contract_employees.contracts_id')
            ->join('clients', 'clients.id', '=', 'contracts.clients_id')
            ->join('company', 'company.id', '=', 'clients.company_id')
            ->select(
                'contracts.number',
                'contracts.date',
                'contracts.amount',
                'company.name as company',
                DB::raw('concat(clients.name," ",clients.lastname) as client')
            )
            ->where('contract_employees.users_id', $id)
            ->whereBetween('contracts.date', [$fechaInicio, $fechaFinal])
            ->get();


        $recaudado = DB::table('receipts')
            ->join('contracts', 'receipts.contracts_id', '=', 'contracts.id')
            ->join('contract_employees', 'contracts.id', '=', 'contract_employees.contracts_id')
            ->join('clients', 'clients.id', '=', 'contracts.clients_id')
            ->join('company', 'company.id', '=', 'clients.company_id')
            ->select(
                'contracts.number',
                'contracts.date',
                'contracts.amount',
                'company.name as company',
                DB::raw('concat(clients.name," ",clients.lastname) as client')
            )
            ->where('contract_employees.users_id', $id)
            ->whereBetween('receipts.date', [$fechaInicio, $fechaFinal])
            ->get();

        $ventas_anticipadas = DB::table('contracts')
            ->join('contract_employees', 'contracts.id', '=', 'contract_employees.contracts_id')
            ->join('clients', 'clients.id', '=', 'contracts.clients_id')
            ->join('company', 'company.id', '=', 'clients.company_id')
            ->select(
                'contracts.number',
                'contracts.date',
                'contracts.amount',
                'company.name as company',
                DB::raw('concat(clients.name," ",clients.lastname) as client')
            )
            ->where('contract_employees.users_id', $id)
            ->where('contracts.advance', '>', 0)
            ->whereBetween('contracts.date', [$fechaInicio, $fechaFinal])
            ->get();

        //return view('reportes.comision', compact('vendedor', 'ventas', 'recaudado', 'ventas_anticipadas', 'fechaInicio', 'fechaFinal', 'sales_percentage', 'collection_percentage'));

        if ($exportar == 1) {
            $pdf = Pdf::loadView('reportes.comision', compact('vendedor', 'ventas', 'recaudado', 'ventas_anticipadas', 'fechaInicio', 'fechaFinal', 'sales_percentage', 'collection_percentage'));
            return $pdf->download('comision.pdf');
        }


        return view('reportes.comision_excel', compact('vendedor', 'ventas', 'recaudado', 'ventas_anticipadas', 'fechaInicio', 'fechaFinal', 'sales_percentage', 'collection_percentage'));
    }

    public function estado_pago(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? NULL;
        $fechaFinal = $request->fecha_final ?? NULL;

        $whereFecha = '';
        if (!empty($fechaInicio) && !empty($fechaFinal)) {
            $whereFecha = "AND contracts.date BETWEEN '$fechaInicio' AND '$fechaFinal'";
        }

        $sql = "SELECT
                        contracts.id,
                        contracts.number,
                        DATE_FORMAT(contracts.date, '%d/%m/%Y') AS date,
                        contracts.amount,
                        contracts.remaining,
                        contracts.monthly_payment,
                        CONCAT(clients.name, ' ', clients.lastname) AS client,
                        company.name AS company,
                        IFNULL(s.sellers, '') AS seller,
                        IFNULL(r.last_date, '') AS fecha_ultimo_recibo,
                        IFNULL(r.last_amount, '') AS monto_ultimo_recibo,
                        IFNULL(r.total_amount, 0) AS monto_total_recibos,
                        contracts.amount - IFNULL(r.total_amount, 0.00) AS saldo_pendiente,
                        DATEDIFF(CURDATE(), COALESCE(r.last_date, contracts.date)) AS dias_desde_ultimo_pago
                    FROM contracts
                    INNER JOIN statuses ON statuses.id = contracts.statuses_id
                    INNER JOIN clients ON clients.id = contracts.clients_id
                    INNER JOIN company ON company.id = clients.company_id
                    LEFT JOIN (
                        SELECT
                            contract_employees.contracts_id,
                            GROUP_CONCAT(CONCAT(users.name, ' ', users.last_name) SEPARATOR ', ') AS sellers
                        FROM contract_employees
                        INNER JOIN users ON users.id = contract_employees.users_id
                        GROUP BY contract_employees.contracts_id
                    ) AS s ON s.contracts_id = contracts.id
                    LEFT JOIN (
                        SELECT
                            contracts_id,
                            MAX(date) AS last_date,
                            SUM(amount) AS total_amount,
                            SUBSTRING_INDEX(
                                GROUP_CONCAT(amount ORDER BY date DESC),
                                ',', 1
                            ) AS last_amount
                        FROM receipts
                        WHERE date <= CURDATE()
                        GROUP BY contracts_id
                    ) AS r ON r.contracts_id = contracts.id
                    WHERE contracts.statuses_id IN (4, 10)
                    $whereFecha
                    AND contracts.remaining > 0;
                    ";

        $registros = DB::select($sql);

        return view('reportes.estado_pago', compact('registros', 'fechaInicio', 'fechaFinal'));
    }

    public function existencia(Request $request)
    {

        // Obtener bodegas activas
        $bodegas = Bodega::where('statuses_id', 2)->get();
        $productosId = Stock::pluck('products_id')->toArray();

        // Iniciar SELECT con el nombre del producto
        $select = [
            DB::raw("CONCAT(products.sku, ' - ', products.description, ' - ', products.color , ' (', brands.name,')' ) as product")
        ];

        // Agregar una columna dinámica por cada bodega
        foreach ($bodegas as $bodega) {
            $alias = 'bodega_' . $bodega->id;
            $select[] = DB::raw("SUM(CASE WHEN warehouses.id = {$bodega->id} THEN stock.quantity ELSE 0 END) AS `$alias`");
        }

        // Ejecutar la consulta
        $datos = DB::table('stock')
            ->join('products', 'stock.products_id', '=', 'products.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id')
            ->join('warehouses', 'stock.warehouses_id', '=', 'warehouses.id')
            ->selectRaw(implode(', ', $select))
            ->whereIn('stock.products_id', $productosId)
            ->where('products.track_inventory', 1)
            ->groupBy('products.id', 'products.description')
            ->orderBy('products.description')
            ->get();

        return view('reportes.existencia', compact('datos', 'bodegas'));
    }

    public function ventas(Request $request)
    {

        $fechaInicio = $request->fechaInicio ?? Carbon::now()->startOfMonth()->toDateString();
        $fechaFinal = $request->fechaFinal ?? Carbon::now()->endOfMonth()->toDateString();

        $contratos = Contrato::whereBetween('date',[$fechaInicio,$fechaFinal])->get();

        $exportar = $request->exportar ?? 1;


        if ($exportar == 1) {
            return view('reportes.ventas', compact('contratos', 'fechaInicio','fechaFinal'));
        }

        return view('reportes.ventas_excel', compact('contratos', 'fechaInicio','fechaFinal'));


        /*
        // Obtener bodegas activas
        $bodegas = Bodega::where('statuses_id', 2)->get();
        $productosId = Stock::pluck('products_id')->toArray();

        // Iniciar SELECT con el nombre del producto
        $select = [
            DB::raw("CONCAT(products.sku, ' - ', products.description, ' - ', products.color , ' (', brands.name,')' ) as product")
        ];

        // Agregar una columna dinámica por cada bodega
        foreach ($bodegas as $bodega) {
            $alias = 'bodega_' . $bodega->id;
            $select[] = DB::raw("SUM(CASE WHEN warehouses.id = {$bodega->id} THEN stock.quantity ELSE 0 END) AS `$alias`");
        }

        // Ejecutar la consulta
        $datos = DB::table('stock')
            ->join('products', 'stock.products_id', '=', 'products.id')
            ->join('brands', 'products.brands_id', '=', 'brands.id')
            ->join('warehouses', 'stock.warehouses_id', '=', 'warehouses.id')
            ->selectRaw(implode(', ', $select))
            ->whereIn('stock.products_id', $productosId)
            ->where('products.track_inventory', 1)
            ->groupBy('products.id', 'products.description')
            ->orderBy('products.description')
            ->get();

        return view('reportes.existencia', compact('datos', 'bodegas'));*/
    }
}
