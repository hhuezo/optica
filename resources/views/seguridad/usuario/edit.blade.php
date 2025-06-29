<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar usuario</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <form method="POST" action="{{ route('user.update', $item->id) }}">
                @method('PUT')
                @csrf
                <div class="modal-body">
                    <div class="row gy-4">

                        <div class="col-12">
                            <label class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="user_name" value="{{ $item->user_name }}"
                                required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{ $item->name }}"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="last_name" value="{{ $item->last_name }}"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Rol</label>
                            <select class="form-select" name="role_id" required>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol->id }}"
                                        {{ $item->hasRole($rol->name) == true ? 'selected' : '' }}>
                                        {{ $rol->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Comisión venta %</label>
                            <input type="number" step="0.01" class="form-control" name="sales_percentage" value="{{ $item->sales_percentage }}">
                        </div>

                         <div class="col-12">
                            <label class="form-label">Comisión cobro %</label>
                            <input type="text" step="0.01"  class="form-control" name="collection_percentage" value="{{ $item->collection_percentage }}">
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
