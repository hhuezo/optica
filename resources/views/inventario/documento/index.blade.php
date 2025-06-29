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
                        Listado de documentos
                    </div>
                    <div class="prism-toggle">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create">Nuevo</button>
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
                        <table id="documentos-table" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Documento</th>
                                    <th>Tipo</th>
                                    <th>Bodega</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>



    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Crear documento</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('documento.store') }}">
                    @csrf
                    <div class="modal-body">


                        <div class="row gy-4">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Tipo Documento</label>
                                <select class="form-select" name="document_types_id" id="document_types_id"
                                    onchange="toggleToWarehouse(this.value)" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($tipos_documento as $tipo)
                                        <option value="{{ $tipo->id }}">
                                            {{ $tipo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Bodega</label>
                                <select class="form-select" name="warehouses_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}"
                                            {{ old('warehouses_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12" id="divToWarehouse" style="display: none">
                                <label for="input-label" class="form-label">Bodega destino</label>
                                <select class="form-select" name="to_warehouse_id" id="to_warehouse_id">
                                    <option value="">Seleccione</option>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}"
                                            {{ old('warehouses_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Documento:</label>
                                <input type="text" class="form-control" name="doc_number"
                                    value="{{ old('doc_number') }}" required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Justificación:</label>
                                <input type="text" class="form-control" name="justification"
                                    value="{{ old('justification') }}" required>
                            </div>


                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>


                </form>
            </div>

            </form>
        </div>
    </div>


    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Modificar documento</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-edit" method="POST">
                    <div class="modal-body">

                        <div id="validation-errors" class="alert alert-danger" style="display:none;"></div>
                        <div class="row gy-4">

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Documento:</label>
                                <input type="hidden" class="form-control" name="id" id="edit_id" required>
                                <input type="text" class="form-control" name="doc_number" id="doc_number" required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Justificación:</label>
                                <input type="text" class="form-control" name="justification" id="justification"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Bodega</label>
                                <select class="form-select" name="warehouses_id" id="warehouses_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}">
                                            {{ $bodega->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Estado</label>
                                <select class="form-select" name="statuses_id" id="statuses_id" required>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">
                                            {{ $estado->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btnEdit" onclick="updateDocumento()"
                            class="btn btn-primary">Guardar</button>
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
            expandMenuAndHighlightOption('inventarioMenu', 'documentoOption');


            $('#documentos-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('documento/data') }}',
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
                        copy: "Copiar",
                        colvis: "Visibilidad",
                        print: "Imprimir",
                        excel: "Excel",
                        pdf: "PDF"
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'documents.id'
                    },
                    {
                        data: 'doc_number',
                        name: 'documents.doc_number'
                    },
                    {
                        data: 'document_types',
                        name: 'document_types.name'
                    },
                    {
                        data: 'warehouses',
                        name: 'warehouses.name'
                    },
                    {
                        data: 'statuses',
                        name: 'statuses.description'
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


        });

        function loadEdit(id, doc_number, justification, warehouse_id, statuses_id) {

            // Rellenar los campos
            document.getElementById('edit_id').value = id;
            document.getElementById('doc_number').value = doc_number;
            document.getElementById('justification').value = justification;
            document.getElementById('warehouses_id').value = warehouse_id;
            document.getElementById('statuses_id').value = statuses_id;

            // Ocultar el botón si el estado es 8
            const btnEdit = document.getElementById('btnEdit');
            if (statuses_id == 8) {
                btnEdit.style.display = 'none';
            } else {
                btnEdit.style.display = 'inline-block'; // o 'block' según diseño
            }

            // Mostrar la modal
            const modal = new bootstrap.Modal(document.getElementById('modal-edit'));
            modal.show();
        }


        function updateDocumento() {
            clearValidationErrors();

            const id = document.getElementById('edit_id').value;
            const doc_number = document.getElementById('doc_number').value;
            const justification = document.getElementById('justification').value;
            const warehouses_id = document.getElementById('warehouses_id').value;
            const statuses_id = document.getElementById('statuses_id').value;

            const url = `{{ url('documento') }}/${id}`;

            const data = new URLSearchParams();
            data.append('_method', 'PUT');
            data.append('_token', '{{ csrf_token() }}');
            data.append('doc_number', doc_number);
            data.append('justification', justification);
            data.append('warehouses_id', warehouses_id);
            data.append('statuses_id', statuses_id);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: data
                })
                .then(async response => {
                    if (response.status === 422) {
                        const errorData = await response.json();
                        showValidationErrors(errorData.errors);
                        throw new Error('Errores de validación');
                    }
                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error('Error inesperado:\n' + text);
                    }
                    return response.json();
                })
                .then(data => {
                    toastr.success(data.message || 'Documento actualizado correctamente.');

                    const modal = bootstrap.Modal.getInstance(document.getElementById('modal-edit'));
                    modal.hide();

                    $('#documentos-table').DataTable().ajax.reload(null, false);
                })
                .catch(error => {
                    //console.error('Error:', error);
                    // Puedes mostrar un alert general aquí si quieres
                });
        }


        function clearValidationErrors() {
            const errorContainer = document.getElementById('validation-errors');
            if (errorContainer) {
                errorContainer.innerHTML = '';
                errorContainer.style.display = 'none';
            }
        }

        function showValidationErrors(errors) {
            const errorContainer = document.getElementById('validation-errors');
            if (!errorContainer) return;

            let html = '<ul class="mb-0">';
            for (const field in errors) {
                errors[field].forEach(msg => {
                    html += `<li>${msg}</li>`;
                });
            }
            html += '</ul>';

            errorContainer.innerHTML = html;
            errorContainer.style.display = 'block';
        }



        function toggleToWarehouse(id) {
            const divToWarehouse = document.getElementById('divToWarehouse');

            if (id == 5) {
                divToWarehouse.style.display = 'block';
            } else {
                divToWarehouse.style.display = 'none';
            }
        }
    </script>
    <!-- End:: row-1 -->
@endsection
