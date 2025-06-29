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
                        Modificación rol
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('seguridad/role/') }}">
                            <button class="btn btn-primary"><i class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('role.update', $role->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">


                            <div class="row gy-4">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Nombre:</label>
                                    <input type="text" class="form-control" name="name" value="{{ $role->name }}"
                                        required>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>

                    </form>
                </div>


                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Permisos
                    </div>
                </div>
                <div class="card-body">

                    <div class="modal-body">


                        <div class="row gy-4">
                            @foreach ($permisos as $item)
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <div class="d-flex align-items-center">
                                        <label class="switch me-2">
                                            <input type="checkbox"
                                                onchange="toggleUpdatePermission({{ $role->id }},{{ $item->id }})"
                                                {{ $role->hasPermissionTo($item->name) ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="input-label">{{ $item->name }}</label>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                </div>



            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('seguridadMenu', 'roleOption');
        });

        function toggleUpdatePermission(roleId, permissionId) {
            fetch(`{{ url('seguridad/role/update_permission') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        'role_id': roleId,
                        'permission_id': permissionId,
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
    </script>
@endsection
