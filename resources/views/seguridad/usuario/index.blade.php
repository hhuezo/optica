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
                        Listado de usuarios
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
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    {{-- <th>Correo</th> --}}
                                    <th>Rol</th>
                                    <th>Comisión venta %</th>
                                    <th>Comisión cobro %</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $item)
                                    <tr>
                                        <td>{{ $item->username }}</td>
                                        <td>{{ $item->name }}</td>
                                        {{-- <td>{{ $item->email }}</td> --}}
                                        <td>{{ $item->getRoleNames()->implode(', ') }}</td>
                                        <td>
                                            {{ $item->sales_percentage !== null ? $item->sales_percentage . '%' : '' }}
                                        </td>
                                        <td>
                                            {{ $item->collection_percentage !== null ? $item->collection_percentage . '%' : '' }}
                                        </td>


                                        {{-- <td><label class="switch">
                                                <input type="checkbox" {{ $item->active == 1 ? 'checked' : '' }}
                                                    onchange="toggleUserActive({{ $item->id }})">
                                                <span class="slider round"></span>
                                            </label></td> --}}

                                        <td><button class="btn btn-sm btn-info btn-wave" data-bs-toggle="modal"
                                                data-bs-target="#modal-edit-{{ $item->id }}">
                                                &nbsp;<i class="ri-edit-line"></i>&nbsp;</button>

                                            <button class="btn btn-sm btn-warning btn-wave" data-bs-toggle="modal"
                                                data-bs-target="#modal-change-password-{{ $item->id }}">
                                                &nbsp;<i class="ri-lock-line"></i>&nbsp;</button>
                                        </td>
                                    </tr>
                                    @include('seguridad.usuario.edit')
                                    @include('seguridad.usuario.password')
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
                    <h6 class="modal-title" id="exampleModalLgLabel">Crear usuario</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('user.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row gy-4">
                            <div class="col-12">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Rol</label>
                                <select class="form-select" name="role_id" required>
                                    @foreach ($roles as $rol)
                                        <option value="{{ $rol->id }}"
                                            {{ old('role') == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->name }}
                                        </option>
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
        </div>
    </div>











    <script src="{{ asset('assets/libs/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/dataTables/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('seguridadMenu', 'usuarioOption');

            $('#datatable-basic').DataTable({
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

        function toggleUserActive(id) {
            fetch(`{{ url('seguridad/user') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}'
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
        }



        function changePassword(id) {
            document.getElementById('edit_pass_id').value = id;
        }
    </script>
    <!-- End:: row-1 -->
@endsection
