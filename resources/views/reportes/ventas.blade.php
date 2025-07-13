@extends ('menu')
@section('content')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- DataTables Buttons CSS -->
    <link href="{{ asset('assets/libs/dataTables/buttons.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>





    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Ventas {{ !empty($fechaInicio) ? date('d/m/Y', strtotime($fechaInicio)) : '' }}
                        {{ !empty($fechaFinal) ? ' - ' . date('d/m/Y', strtotime($fechaFinal)) : '' }}

                    </div>
                    <div class="prism-toggle d-flex gap-2">
                        <!-- Botón Excel -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-filtro"
                            class="btn btn-sm btn-info btn-wave" id="btn-export-excel">
                            <i class="ri-filter-3-line"></i> Filtrar
                        </a>

                    </div>
                </div>
                <div class="card-body">


                    <div class="table-responsive">


                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                    <th>Tipo pago</th>
                                    <th>Pago mensual</th>
                                    <th>Vendedor</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($totalGeneral = 0)
                                @foreach ($contratos as $contrato)
                                    @php($total = 0)
                                    @foreach ($contrato->detalles as $detalle)
                                        @php($subtotal = 0)
                                        @if (!empty($detalle->quantity) && !empty($detalle->price))
                                            @php($subtotal += $detalle->quantity * $detalle->price)
                                        @endif
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($contrato->date)) }} </td>
                                            <td>{{ $contrato->cliente->name ?? '' }}
                                                {{ $contrato->cliente->lastname ?? '' }}</td>
                                            <td>{{ $detalle->producto->sku ?? '' }}
                                                {{ $detalle->producto->description ?? '' }}
                                                {{ $detalle->producto->color ?? '' }}</td>
                                            <td>{{ $detalle->quantity }}</td>
                                            <td style="text-align: right">${{ $detalle->price }}</td>
                                            <td style="text-align: right">
                                                ${{ number_format($subtotal, 2) }}
                                            </td>

                                            <td>{{ $contrato->payment_type }}</td>
                                            <td style="text-align: right">${{ $contrato->monthly_payment }}</td>
                                            <td>
                                                {{ $contrato->vendedores->map(fn($v) => $v->name . ' ' . $v->last_name)->implode(', ') }}
                                            </td>
                                            <td>{{ $contrato->estado->description ?? '' }}</td>
                                        </tr>
                                        @php($total += $subtotal)
                                    @endforeach
                                    <tr>
                                        <td colspan="5" style="text-align: right"><strong>TOTAL CONTRATO
                                                {{ $contrato->number }}</strong>
                                        </td>
                                        <td style="text-align: right"><strong>${{ number_format($total, 2) }}</strong></td>
                                        <td colspan="4"></td>
                                    </tr>
                                    @php($totalGeneral += $total)
                                @endforeach

                                <tr>
                                    <td colspan="5" style="text-align: right; background-color: #f8f9fa;"><strong>TOTAL
                                            GENERAL</strong></td>
                                    <td style="text-align: right; background-color: #f8f9fa;">
                                        <strong>${{ number_format($totalGeneral, 2) }}</strong>
                                    </td>
                                    <td colspan="4" style="background-color: #f8f9fa;"></td>
                                </tr>
                            </tbody>
                        </table>


                    </div>
                </div>

            </div>
        </div>

    </div>


    <div class="modal fade" id="modal-filtro" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Filtrar..</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ url('reportes/ventas') }}">
                    <div class="modal-body">


                        <div class="row gy-4">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha inicio</label>
                                <input type="date" class="form-control" name="fechaInicio" value="{{ $fechaInicio }}"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha final</label>
                                <input type="date" class="form-control" name="fechaFinal" value="{{ $fechaFinal }}"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label class="switch">
                                    <input type="checkbox" name="exportar">
                                    <span class="slider round"></span>
                                </label>&nbsp;
                                <label for="input-label" class="form-label">Exportar excel</label>

                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                    </div>

                </form>
            </div>

            </form>
        </div>
    </div>




    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>



    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('reportesMenu', 'reporteVentasOption');

        });
    </script>
    <!-- End:: row-1 -->
@endsection



{{-- <table class="head" cellspacing="0" cellspadding="0">
    <tr>
        <td class="title" colspan="6">
            <span> <strong>Reporte de ventas {{ date('d/m/Y', strtotime($fechaInicio)) }} -
                    {{ date('d/m/Y', strtotime($fechaFinal)) }} </strong></span>
        </td>
    </tr>
</table>
<br />
<table class="table table-striped" border="1" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
            <th>Tipo pago</th>
            <th>Pago mensual</th>
            <th>Vendedor</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @php($totalGeneral = 0)
        @foreach ($contratos as $contrato)
            @php($total = 0)
            @foreach ($contrato->detalles as $detalle)
                @php($subtotal = 0)
                @if (!empty($detalle->quantity) && !empty($detalle->price))
                    @php($subtotal += $detalle->quantity * $detalle->price)
                @endif
                <tr>
                    <td>{{ date('d/m/Y', strtotime($contrato->date)) }} </td>
                    <td>{{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}</td>
                    <td>{{ $detalle->producto->sku ?? '' }} {{ $detalle->producto->description ?? '' }} {{ $detalle->producto->color ?? '' }}</td>
                    <td>{{ $detalle->quantity }}</td>
                    <td style="text-align: right">${{ $detalle->price }}</td>
                    <td style="text-align: right">
                        ${{ number_format($subtotal, 2) }}
                    </td>

                    <td>{{ $contrato->payment_type }}</td>
                    <td style="text-align: right">${{ $contrato->monthly_payment }}</td>
                    <td>
                        {{ $contrato->vendedores->map(fn($v) => $v->name . ' ' . $v->last_name)->implode(', ') }}
                    </td>
                    <td>{{ $contrato->estado->description ?? '' }}</td>
                </tr>
                @php($total += $subtotal)
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right"><strong>TOTAL CONTRATO {{ $contrato->number }}</strong>
                </td>
                <td style="text-align: right"><strong>${{ number_format($total, 2) }}</strong></td>
                <td colspan="4"></td>
            </tr>
            @php($totalGeneral += $total)
        @endforeach

        <tr>
            <td colspan="5" style="text-align: right; background-color: #f8f9fa;"><strong>TOTAL GENERAL</strong></td>
            <td style="text-align: right; background-color: #f8f9fa;"><strong>${{ number_format($totalGeneral, 2) }}</strong></td>
            <td colspan="4" style="background-color: #f8f9fa;"></td>
        </tr>
    </tbody>
</table> --}}
