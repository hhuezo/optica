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
                        Estado de pagos {{ !empty($fechaInicio) ? date('d/m/Y', strtotime($fechaInicio)) : '' }}
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
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha servicio</th>
                                    <th>Cliente</th>
                                    <th>Cantidad</th>
                                    <th>Vendedor</th>
                                    <th>Fecha último pago</th>
                                    <th>Sin vencer</th>
                                    <th>30 dias</th>
                                    <th>60 dias</th>
                                    <th>90 dias</th>
                                    <th>120 dias</th>
                                    <th>Más dias</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registros as $item)
                                    <tr>
                                        <td>{{ $item->number }}</td>
                                        <td>{{ $item->date }}</td>
                                        <td>{{ $item->client }}</td>
                                        <td class="text-end">
                                            {{ $item->amount !== null ? '$' . number_format($item->amount, 2) : '' }}</td>
                                        <td>{{ $item->seller }}</td>
                                        <td>
                                            {{ $item->fecha_ultimo_recibo ? date('d/m/Y', strtotime($item->fecha_ultimo_recibo)) : '' }}
                                        </td>

                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago < 30)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago >= 30 && $item->dias_desde_ultimo_pago < 60)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago >= 60 && $item->dias_desde_ultimo_pago < 90)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago >= 90 && $item->dias_desde_ultimo_pago < 120)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago == 120)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            @if ($item->dias_desde_ultimo_pago > 120)
                                                {{ $item->monthly_payment !== null ? '$' . number_format($item->monthly_payment, 2) : '' }}
                                            @endif
                                        </td>


                                    </tr>
                                @endforeach

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
                    <h6 class="modal-title" id="exampleModalLgLabel">Filtrar</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ url('reportes/estado_pago') }}">
                    <div class="modal-body">


                        <div class="row gy-4">
                            @php
                                use Carbon\Carbon;
                                $fechaInicio = Carbon::now()->subMonth()->format('Y-m-d');
                                $fechaFinal = Carbon::now()->format('Y-m-d');
                            @endphp

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="{{ $fechaInicio }}"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha final</label>
                                <input type="date" class="form-control" name="fecha_final" value="{{ $fechaFinal }}"
                                    required>
                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>

                </form>
            </div>

            </form>
        </div>
    </div>


    <div class="modal fade" id="modal-exportar" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Filtrar</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ url('reportes/estado_pago') }}">
                    <div class="modal-body">


                        <div class="row gy-4">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio">
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha final</label>
                                <input type="date" class="form-control" name="fecha_final">
                            </div>
                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>

                </form>
            </div>

            </form>
        </div>
    </div>


    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>


    <script src="{{ asset('assets/libs/dataTables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/buttons.print.min.js') }}"></script>

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('ventasMenu', 'estadoPagoOption');

            $('#datatable-basic').DataTable({
                ordering: false,
                dom: 'Bfrtip', // ⬅️ Esto activa los botones arriba de la tabla
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-2-line"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        filename: 'estado_pago', // ← Nombre del archivo .xlsx
                        title: 'Estado de pagos' // ← Título dentro del Excel
                    },

                    {
                        extend: 'pdfHtml5',
                        text: '<i class="ri-file-pdf-2-line"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        filename: 'estado_pago', // ← Nombre del archivo .pdf
                        title: 'Estado de pagos', // ← Título dentro del PDF
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="ri-printer-line"></i> Imprimir',
                        className: 'btn btn-secondary btn-sm'
                    }
                ],
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    loadingRecords: "Cargando...",
                    zeroRecords: "No se encontraron resultados",
                    emptyTable: "Ningún dato disponible en esta tabla",
                    paginate: {
                        first: "<<",
                        previous: "<",
                        next: ">",
                        last: ">>"
                    },
                    aria: {
                        sortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sortDescending: ": Activar para ordenar la columna de manera descendente"
                    },
                    buttons: {
                        copy: 'Copiar',
                        colvis: 'Visibilidad',
                        print: 'Imprimir',
                        excel: 'Exportar Excel',
                        pdf: 'Exportar PDF'
                    }
                }
            });



        });
    </script>
    <!-- End:: row-1 -->
@endsection
