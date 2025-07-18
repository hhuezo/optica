<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\seguridad\Role;
use App\Models\ventas\Contrato;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Obtener el mes y año actual
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $totalVentas = Contrato::whereIn('statuses_id', [5, 10])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('amount');

        $clientesAtendidos = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereYear('contracts.created_at', $year)
            ->whereMonth('contracts.created_at', $month)
            ->select('clients.id')
            ->groupBy('clients.id')
            ->get()
            ->count();

        $totalSalesPublico = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereYear('contracts.created_at', $year)
            ->whereMonth('contracts.created_at', $month)
            ->whereIn('contracts.statuses_id', [5, 10])
            ->where('company.company_category_id', 1) // 1 sector publico
            ->sum('contracts.amount');

        $totalSalesPrivado = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereYear('contracts.created_at', $year)
            ->whereMonth('contracts.created_at', $month)
            ->whereIn('contracts.statuses_id', [5, 10])
            ->where('company.company_category_id', 2) // 1 sector privado
            ->sum('contracts.amount');

        $data = ["totalVentas" => $totalVentas, "clientesAtendidos" => $clientesAtendidos, "totalSalesPublico" => $totalSalesPublico, "totalSalesPrivado" => $totalSalesPrivado];



        $fechaInicio = Carbon::now()->subMonths(11)->startOfMonth(); // Incluye este mes
        $fechaFin = Carbon::now()->endOfMonth();


        $meses = [
            1  => 'Enero',
            2  => 'Febrero',
            3  => 'Marzo',
            4  => 'Abril',
            5  => 'Mayo',
            6  => 'Junio',
            7  => 'Julio',
            8  => 'Agosto',
            9  => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];


        $totalVentas = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereIn('contracts.statuses_id', [5, 10])
            ->whereBetween('contracts.created_at', [$fechaInicio, $fechaFin])
            ->where('company.company_category_id', 1)
            ->select(
                DB::raw('YEAR(contracts.created_at) as anio'),
                DB::raw('MONTH(contracts.created_at) as mes'),
                DB::raw('SUM(contracts.amount) as total')
            )
            ->groupBy(DB::raw('YEAR(contracts.created_at)'), DB::raw('MONTH(contracts.created_at)'))
            ->orderBy(DB::raw('YEAR(contracts.created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(contracts.created_at)'), 'asc')
            ->get();

        $categories = [];
        $values = [];

        foreach ($totalVentas as $item) {
            $valor = $item->anio . '-' . $meses[$item->mes];
            $categories[] = $meses[$item->mes]; // ✅ forma correcta
            $values[] = $item->total + 0;
        }



        $totalVentas = DB::table('contracts')
            ->join('clients', 'contracts.clients_id', '=', 'clients.id')
            ->join('company', 'clients.company_id', '=', 'company.id')
            ->whereIn('contracts.statuses_id', [5, 10])
            ->whereBetween('contracts.created_at', [$fechaInicio, $fechaFin])
            ->where('company.company_category_id', 2)
            ->select(
                DB::raw('YEAR(contracts.created_at) as anio'),
                DB::raw('MONTH(contracts.created_at) as mes'),
                DB::raw('SUM(contracts.amount) as total')
            )
            ->groupBy(DB::raw('YEAR(contracts.created_at)'), DB::raw('MONTH(contracts.created_at)'))
            ->orderBy(DB::raw('YEAR(contracts.created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(contracts.created_at)'), 'asc')
            ->get();

        $valuesPrivado = [];

        foreach ($totalVentas as $item) {
            $valuesPrivado[] = $item->total + 0;
        }


        $fechaFin = Carbon::now()->endOfMonth();
        $fechaInicio = Carbon::now()->subMonths(11)->startOfMonth();

        $totalContratosPorMes = DB::table('contracts')
            ->whereIn('contracts.statuses_id', [5, 10])
            ->whereBetween('contracts.created_at', [$fechaInicio, $fechaFin])
            ->select(
                DB::raw("YEAR(contracts.created_at) as anio"),
                DB::raw("MONTH(contracts.created_at) as mes"),
                DB::raw("COUNT(*) as total_contratos")
            )
            ->groupBy('anio', 'mes')
            ->orderBy('anio', 'asc')
            ->orderBy('mes', 'asc')
            ->get();

        $categoriesContratos = [];
        $valuesContratos = [];

        foreach ($totalContratosPorMes as $item) {
            //$valor = $item->anio . '-' . $meses[$item->mes];
            $categoriesContratos[] = $meses[$item->mes]; // ✅ forma correcta
            $valuesContratos[] = $item->total_contratos;
        }



        return view('home', compact('data', 'categories', 'values', 'valuesPrivado', 'categoriesContratos', 'valuesContratos'));
    }
}
