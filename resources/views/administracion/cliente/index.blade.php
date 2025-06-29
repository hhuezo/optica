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

    <script src="{{ asset('assets/libs/cleave/cleave.min.js') }}"></script>





    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Listado de clientes
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
                        <table id="clientes-table" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Empresa</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Contratos</th>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Nuevo cliente</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('cliente.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}"
                                    required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">DUI</label>
                                <input type="text" class="form-control" name="identification" id="identification"
                                    value="{{ old('identification') }}" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">NIT</label>
                                <input type="text" class="form-control" name="nit" id="nit"
                                    value="{{ old('nit') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Código de empleado</label>
                                <input type="text" class="form-control" name="employee_code"
                                    value="{{ old('employee_code') }}">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Dirección</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Empresa:</label>
                                <select class="form-select" name="company_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}"
                                            {{ old('company_id') == $empresa->id ? 'selected' : '' }}>
                                            {{ $empresa->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Dependencia</label>
                                <input type="text" class="form-control" name="dependencia"
                                    value="{{ old('dependencia') }}">
                            </div>

                        </div>



                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </form>
            </div>

            </form>
        </div>
    </div>



    <!-- Corregido -->
    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Modificar cliente</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarCliente">
                    @csrf
                    <div class="modal-body">
                        <div id="validation-errors" class="alert alert-danger" style="display:none;"></div>
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_name" class="form-label">Nombre</label>
                                <input type="hidden" class="form-control" name="name" id="edit_id">
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_lastname" class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="lastname" id="edit_lastname" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_identification" class="form-label">DUI</label>
                                <input type="text" class="form-control" name="identification"
                                    id="edit_identification" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_nit" class="form-label">NIT</label>
                                <input type="text" class="form-control" name="nit" id="edit_nit">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_employee_code" class="form-label">Código de empleado</label>
                                <input type="text" class="form-control" name="employee_code" id="edit_employee_code">
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="phone" id="edit_phone" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_address" class="form-label">Dirección</label>
                                <input type="text" class="form-control" name="address" id="edit_address" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_company_id" class="form-label">Empresa:</label>
                                <select class="form-select" name="company_id" id="edit_company_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}">{{ $empresa->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="edit_dependencia" class="form-label">Dependencia</label>
                                <input type="text" class="form-control" name="dependencia" id="edit_dependencia">
                            </div>


                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Estado</label>
                                <select class="form-select" name="statuses_id" id="edit_statuses_id">
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">
                                            {{ $estado->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>




                    </div>

                    <div class="modal-footer">
                        <button type="button" onclick="updateCliente()" class="btn btn-primary">Guardar</button>
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



            $('#clientes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('clientes/data') }}',
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
                        name: 'clients.id'
                    },
                    {
                        data: 'company',
                        name: 'company.name'
                    },
                    {
                        data: 'name',
                        name: 'clients.name'
                    },
                    {
                        data: 'lastname',
                        name: 'clients.lastname'
                    },
                    {
                        data: 'phone',
                        name: 'clients.phone'
                    },
                    {
                        data: 'statuses',
                        name: 'statuses.description'
                    },
                    {
                        data: 'contracts',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });



            /*$('#datatable-basic').DataTable({
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
            });*/

            new Cleave('#nit', {
                delimiters: ['-', '-', '-', '-'],
                blocks: [4, 6, 3, 1],
                numericOnly: true
            });


            new Cleave('#identification', {
                delimiters: ['-'],
                blocks: [8, 1],
                numericOnly: true
            });


        });

        function getCliente(id) {


            fetch(`{{ url('cliente/get_cliente') }}/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const cliente = data.data;
                        document.getElementById('edit_id').value = cliente.id || '';
                        document.getElementById('edit_name').value = cliente.name || '';
                        document.getElementById('edit_lastname').value = cliente.lastname || '';
                        document.getElementById('edit_identification').value = cliente.identification || '';
                        document.getElementById('edit_nit').value = cliente.nit || '';
                        document.getElementById('edit_employee_code').value = cliente.employee_code || '';
                        document.getElementById('edit_phone').value = cliente.phone || '';
                        document.getElementById('edit_address').value = cliente.address || '';
                        document.getElementById('edit_company_id').value = cliente.company_id || '';
                        document.getElementById('edit_dependencia').value = cliente.dependencia || '';
                        document.getElementById('edit_statuses_id').value = cliente.statuses_id || '';
                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
        }


        function updateCliente() {
            clearValidationErrors();

            const clienteId = document.getElementById('edit_id').value;
            const form = document.getElementById('formEditarCliente');
            const formData = new FormData(form);
            formData.append('_token', '{{ csrf_token() }}');

            fetch(`{{ url('clientes/update_record') }}/${clienteId}`, {
                    method: 'POST',
                    body: formData
                })
                .then(async response => {
                    if (response.status === 422) {
                        const errorData = await response.json();
                        showValidationErrors(errorData.errors);
                        return Promise.reject('Errores de validación');
                    }
                    if (!response.ok) {
                        const text = await response.text();
                        throw new Error(text || 'Error inesperado');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message, 'Éxito');
                        // Ocultar modal Bootstrap 5
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modal-edit'));
                        modal.hide();

                        // Recargar tabla DataTable
                        $('#clientes-table').DataTable().ajax.reload(null, false);
                    } else {
                        alert('Hubo un error al actualizar el cliente');
                    }
                })
                .catch(error => {
                    if (error !== 'Errores de validación') {
                        console.error('Error:', error);
                        alert('Hubo un error al procesar la solicitud');
                    }
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
    </script>
    <!-- End:: row-1 -->
@endsection
