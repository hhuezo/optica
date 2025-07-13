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
                        Pagos efectuados {{ !empty($fechaInicio) ? date('d/m/Y', strtotime($fechaInicio)) : '' }}
                        {{ !empty($fechaFinal) ? ' - ' . date('d/m/Y', strtotime($fechaFinal)) : '' }}

                    </div>
                    <div class="prism-toggle d-flex gap-2">
                        <!-- Botón Excel -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-filtro"
                            class="btn btn-sm btn-info btn-wave" id="btn-export-excel">
                            <i class="ri-filter-3-line"></i> Filtrardescription
                        </a>

                    </div>
                </div>
                <div class="card-body">


                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Monto</th>
                                    <th>Empresa</th>
                                    <th>Contrato</th>
                                    <th>Monto contrato</th>
                                    <th>Remanente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pagos as $item)
                                    <tr>
                                        <td>{{ $item->receipt_number }}</td>
                                        <td> {{ $item->created_at ? date('d/m/Y', strtotime($item->created_at)) : '' }}</td>
                                        <td>{{ $item->client }}</td>
                                        <td class="text-end">
                                            {{ $item->amount !== null ? '$' . number_format($item->amount, 2) : '' }}</td>
                                        <td>{{ $item->company }} {{ $item->dependencia }}</td>
                                        <td>{{ $item->contract_number }}</td>
                                        <td class="text-end">
                                            {{ $item->amount_contract !== null ? '$' . number_format($item->amount_contract, 2) : '' }}
                                        </td>
                                        <td>{{ $item->remaining }}</td>
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
                    <h6 class="modal-title" id="exampleModalLgLabel">Filtrar..</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ url('reportes/pagos_mensuales') }}">
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
            expandMenuAndHighlightOption('reportesMenu', 'reportePagosOption');

            $('#datatable-basic').DataTable({
                ordering: false,
                dom: 'Bfrtip', // ⬅️ Esto activa los botones arriba de la tabla
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="ri-file-excel-2-line"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        filename: 'pagos', // ← Nombre del archivo .xlsx
                        title: 'Pagos' // ← Título dentro del Excel
                    },

                    {
                        extend: 'pdfHtml5',
                        text: '<i class="ri-file-pdf-2-line"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        filename: 'pagos', // ← Nombre del archivo .pdf
                        title: 'Pagos', // ← Título dentro del PDF
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
