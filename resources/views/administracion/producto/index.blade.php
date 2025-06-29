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
                        Listado de productos
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
                        <table id="datatable-basic" class="table table-striped text-nowrap w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Id</th>
                                    <th>Descripción</th>
                                    <th>Costo</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $item)
                                    <tr>
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>${{ $item->cost }}</td>
                                        <td>{{ $item->estado->description ?? '' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-wave" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit" onclick="getproducto({{ $item->id }})">
                                                &nbsp;<i class="ri-edit-line"></i>&nbsp;</button>
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


    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Nuevo producto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('producto.store') }}">
                    @csrf
                    <div class="modal-body">

                        <div class="row gy-4">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">SKU</label>
                                <input type="text" class="form-control" name="sku" value="{{ old('sku') }}"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Descripción</label>
                                <input type="text" class="form-control" name="description"
                                    value="{{ old('description') }}" required>
                            </div>



                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Costo</label>
                                <input type="numer" step="any" class="form-control" name="cost"
                                    value="{{ old('cost') }}" required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" value="{{ old('color') }}">
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Modelo</label>
                                <input type="text" class="form-control" name="model" value="{{ old('model') }}">
                            </div>


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Marca</label>
                                <select class="form-select" name="brands_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}"
                                            {{ old('brands_id') == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Registra Inventario</label>
                                <select class="form-select" name="track_inventory" required>
                                    <option value="1" {{ old('track_inventory') == '1' ? 'selected' : '' }}>SI
                                    </option>
                                    <option value="0" {{ old('track_inventory') == '0' ? 'selected' : '' }}>NO
                                    </option>
                                </select>

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



    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-modal="true" role="dialog">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Modificar producto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarproducto" method="POST" action="{{ route('producto.update', 0) }}">
                    @csrf
                    @method('PUT')


                    <div class="modal-body">
                        <div class="row gy-4">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_sku" class="form-label">SKU</label>
                                <input type="text" class="form-control" name="sku" id="edit_sku" required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_description" class="form-label">Descripción</label>
                                <input type="text" class="form-control" name="description" id="edit_description"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_cost" class="form-label">Costo</label>
                                <input type="number" step="any" class="form-control" name="cost" id="edit_cost"
                                    required>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_color" class="form-label">Color</label>
                                <input type="text" class="form-control" name="color" id="edit_color">
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_model" class="form-label">Modelo</label>
                                <input type="text" class="form-control" name="model" id="edit_model">
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_brands_id" class="form-label">Marca</label>
                                <select class="form-select" name="brands_id" id="edit_brands_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}">
                                            {{ $marca->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="edit_track_inventory" class="form-label">Registra Inventario</label>
                                <select class="form-select" name="track_inventory" id="edit_track_inventory" required>
                                    <option value="1" {{ old('track_inventory') == '1' ? 'selected' : '' }}>SI
                                    </option>
                                    <option value="0" {{ old('track_inventory') == '0' ? 'selected' : '' }}>NO
                                    </option>
                                </select>
                            </div>


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Estado</label>
                                <select class="form-select" name="statuses_id" id="edit_statuses_id">
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}"
                                            {{ $item->statuses_id == $estado->id ? 'selected' : '' }}>
                                            {{ $estado->description }}</option>
                                    @endforeach
                                </select>
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





    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('administracionMenu', 'productoOption');


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

        function getproducto(id) {
            const form = document.getElementById('formEditarproducto');
            const currentAction = form.getAttribute('action');

            // Reemplaza el número al final de la URL (el ID)
            const newAction = currentAction.replace(/\/\d+$/, `/${id}`);
            form.setAttribute('action', newAction);

            fetch(`{{ url('producto') }}/${id}`, {
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
                        const producto = data.data;
                        document.getElementById('edit_sku').value = producto.sku || '';
                        document.getElementById('edit_description').value = producto.description || '';
                        document.getElementById('edit_cost').value = producto.cost || '';
                        document.getElementById('edit_color').value = producto.color || '';
                        document.getElementById('edit_model').value = producto.model || '';

                        // Select: marcas_id
                        const marcaSelect = document.getElementById('edit_brands_id');
                        if (marcaSelect) {
                            marcaSelect.value = producto.brands_id;
                        }

                        // Select: track_inventory (1 o 0)
                        const inventarioSelect = document.getElementById('edit_track_inventory');
                        if (inventarioSelect) {
                            inventarioSelect.value = producto.track_inventory;
                        }

                        // Select: statuses_id
                        const estadoSelect = document.getElementById('edit_statuses_id');
                        if (estadoSelect) {
                            estadoSelect.value = producto.statuses_id;
                        }

                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
        }
    </script>







    <!-- End:: row-1 -->
@endsection
