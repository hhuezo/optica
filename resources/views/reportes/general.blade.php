@extends('menu')
@section('content')
    <!-- Start::page-header -->
    <div class="d-flex align-items-center justify-content-between page-header-breadcrumb flex-wrap gap-2">
        {{-- <div>
            <h1 class="page-title fw-medium fs-18 mb-0">Dashboard</h1>
        </div> --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- <div class="form-group">
                <div class="input-group">
                    <div class="input-group-text bg-white border"> <i class="ri-calendar-line"></i> </div>
                    <input type="text" class="form-control breadcrumb-input" id="daterange"
                        placeholder="Search By Date Range">
                </div>
            </div> --}}
            {{-- <div class="btn-list">
                <button class="btn btn-white btn-wave">
                    <i class="ri-filter-3-line align-middle me-1 lh-1"></i> Filter
                </button>
                <button class="btn btn-primary btn-wave me-0">
                    <i class="ri-share-forward-line me-1"></i> Share
                </button>
            </div> --}}
        </div>
    </div>
    <!-- End::page-header -->

    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-xxl-4 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="text-muted d-block mb-1">Deuda sector Público</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($totalPublico, 2) }}
                                    </h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-primary2">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="d-block text-muted mb-1">Deuda sector Privado</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($totalPrivado, 2) }}</h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-info">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="text-muted d-block mb-1">Total deuda</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($totalPrivado + $totalPublico, 2) }}</h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-success">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="col-xl-6">

            <div class="col-xl-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Deuda del Sector Público
                        </div>
                        <div class="dropdown">

                            <a href="{{ url('reportes/sector/1/1') }}" target="_blank" class="btn btn-info"><i
                                    class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ url('reportes/sector/1/2') }}" target="_blank" class="btn btn-success"><i
                                    class="bi bi-file-earmark-spreadsheet"></i>
                            </a>

                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="datatable-basic" class="table table-striped table-bordered text-nowrap">
                                <thead class="table-info">
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultPublico as $publico)
                                        <tr>
                                            <td>
                                                <span class="fw-medium top-category-name one">{{ $publico->name }}</span>
                                            </td>
                                            <td style="text-align: right">
                                                <span class="fw-medium">${{ number_format($publico->total, 2) }}</span>
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

        <div class="col-xl-6">

            <div class="col-xl-12">
                <div class="card custom-card overflow-hidden">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Deuda del Sector Privado
                        </div>
                        <div class="dropdown">
                            <a href="{{ url('reportes/sector/2/1') }}" target="_blank" class="btn btn-info"><i
                                    class="bi bi-file-earmark-pdf"></i>
                            </a>
                            <a href="{{ url('reportes/sector/2/2') }}" target="_blank" class="btn btn-success"><i
                                    class="bi bi-file-earmark-spreadsheet"></i>
                            </a>

                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="datatable-basic2" class="table table-striped table-bordered text-nowrap">
                                <thead class="table-info">
                                    <tr>
                                        <th>Empresa</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultPrivado as $privado)
                                        <tr>
                                            <td>
                                                <span class="fw-medium top-category-name one">{{ $privado->name }}</span>
                                            </td>
                                            <td style="text-align: right">
                                                <span class="fw-medium">${{ number_format($privado->total, 2) }}</span>
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




        <div class="col-xl-7">
            <div class="card custom-card overflow-hidden">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Estado de cuenta por empresa
                    </div>
                    <div class="dropdown">
                        <a href="{{ url('reportes/estado_cuenta_empresa/1') }}" class="btn btn-info"><i
                                class="bi bi-file-earmark-pdf"></i>
                        </a>
                        <a href="{{ url('reportes/estado_cuenta_empresa/2') }}" class="btn btn-success"><i
                                class="bi bi-file-earmark-spreadsheet"></i>
                        </a>

                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="datatable-basic3" class="table table-striped text-nowrap w-100">
                            <thead class="table-info">
                                <tr>
                                    <th>Empresa</th>
                                    <th>Monto</th>
                                    <th>Remanente</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empresas as $empresa)
                                    <tr>
                                        <td>
                                            {{ $empresa->name }}
                                        </td>
                                        <td>
                                            ${{ number_format($empresa->amount, 2) }}
                                        </td>
                                        <td style="text-align: right">
                                            ${{ number_format($empresa->remaining, 2) }}
                                        </td>
                                        <td>
                                            <a href="{{ url('reportes/estado_cuenta_por_empresa') }}/{{ $empresa->id }}/1"
                                                class="btn btn-info"><i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            <a href="{{ url('reportes/estado_cuenta_por_empresa') }}/{{ $empresa->id }}/2"
                                                class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card custom-card overflow-hidden">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Comisiones
                    </div>
                    <div class="dropdown">


                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="datatable-basic4" class="table table-striped text-nowrap w-100">
                            <thead class="table-info">
                                <tr>
                                    <th>Asesor</th>
                                    <th>Comision venta</th>
                                    <th>Comision cobro</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asesores as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }} {{ $item->last_name }}
                                        </td>
                                        <td>{{ $item->sales_percentage ?? '' }}</td>
                                        <td>{{ $item->collection_percentage ?? '' }}</td>
                                        <td>
                                            <button class="btn btn-info" data-bs-toggle="modal"
                                                data-bs-target="#modal-comision-{{ $item->id }}"><i
                                                    class="bi bi-file-earmark-pdf"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @include('modal_comision')
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            expandMenuAndHighlightOption('reportesMenu', 'reporteGeneralOption');

            $('#datatable-basic').DataTable({
                //paging: false, // Quita paginación
                //searching: false, // Quita el cuadro de búsqueda
                info: false, // Quita el texto de "Mostrando registros..."
                //ordering: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
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

            $('#datatable-basic2').DataTable({
                //paging: false, // Quita paginación
                //searching: false, // Quita el cuadro de búsqueda
                info: false, // Quita el texto de "Mostrando registros..."
                //ordering: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
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


            $('#datatable-basic3').DataTable({
                //paging: false, // Quita paginación
                //searching: false, // Quita el cuadro de búsqueda
                info: false, // Quita el texto de "Mostrando registros..."
                //ordering: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
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


            $('#datatable-basic4').DataTable({
                //paging: false, // Quita paginación
                //searching: false, // Quita el cuadro de búsqueda
                info: false, // Quita el texto de "Mostrando registros..."
                //ordering: false,
                language: {
                    processing: "Procesando...",
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    infoFiltered: "(filtrado de un total de _MAX_ registros)",
                    infoPostFix: "",
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
