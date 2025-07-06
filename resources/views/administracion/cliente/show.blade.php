@extends ('menu')
@section('content')
    <!-- DataTables CSS -->
    <link href="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.css') }}" rel="stylesheet">

    <!-- Toastr CSS -->
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('assets/libs/toast/toastr.min.js') }}"></script>




    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        CONTRATOS - {{ $cliente->name }} {{ $cliente->lastname }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('cliente') }}"> <button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>
                <div class="card-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <script>
                            toastr.success("{{ session('success') }}");
                        </script>
                    @endif

                    @if (session('error'))
                        <script>
                            toastr.error("{{ session('error') }}");
                        </script>
                    @endif

                    <div class="table-responsive">
                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cliente->contratos as $item)
                                    <tr>
                                        <td>{{ $item->number }}</td>
                                        <td>{{ $item->date ? date('d/m/Y', strtotime($item->date)) : '' }}</td>
                                        <td>${{ $item->amount }}</td>
                                        <td>{{ $item->estado->description ?? '' }}</td>
                                        <td>
                                            <a href="{{ url('cliente/contrato_show') }}/{{ $item->id }}"> <button
                                                    class="btn btn-sm btn-warning btn-wave">
                                                    &nbsp;<i class="bi bi-card-list"></i>&nbsp;</button></a>

                                            &nbsp;
                                            <a href="{{ url('cliente/contrato_reporte') }}/{{ $item->id }}" target="_blank"> <button
                                                    class="btn btn-sm btn-info btn-wave">
                                                    &nbsp;<i class="bi bi-file-earmark-pdf-fill"></i>&nbsp;</button></a>

                                                     &nbsp;
                                            <a href="{{ url('cliente/contrato_receta') }}/{{ $item->id }}" target="_blank"> <button
                                                    class="btn btn-sm btn-success btn-wave">
                                                    &nbsp;<i class="bi bi-card-checklist"></i>&nbsp;</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer" style="text-align: center">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-create">+</button>
                </div>

            </div>
        </div>

    </div>


    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Nuevo contrato</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('cliente.contrato.store', $cliente->id) }}" id="form-create">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-4">
                            <div class="col-6">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                    value="{{ old('date') }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Número</label>
                                <input type="text" class="form-control" name="number" value="{{ old('number') }}"
                                    required>
                            </div>


                            <div class="col-6">
                                <label for="input-label" class="form-label">Tipo pago</label>
                                <select name="payment_type" class="form-select">
                                    <option value="CONTADO">Contado</option>
                                    <option value="CREDITO">Crédito</option>
                                </select>
                            </div>


                            <div class="col-6">
                                <label for="input-label" class="form-label">Plazo</label>
                                <input type="number" step="1" class="form-control" name="term" id="term"
                                    value="{{ old('term') }}" required>
                            </div>



                            <div class="col-6">
                                <label for="input-label" class="form-label">Bodega</label>
                                <select name="warehouses_id" class="form-select" onchange="getProductos(this.value)"
                                    required>
                                    <option value="">Seleccione</option>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}"
                                            {{ old('warehouses_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->name }}
                                        </option>
                                    @endforeach
                                </select>

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

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('ventasMenu', 'clienteOption');


            $('#datatable-basic').DataTable({
                //paging: false, // Quita paginación
                //searching: false, // Quita el cuadro de búsqueda
                info: false, // Quita el texto de "Mostrando registros..."
                ordering: false,
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


@endsection
