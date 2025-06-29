<div class="modal fade" id="modal-edit-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar permiso</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('permission.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">


                    <div class="row gy-4">
                        <input type="hidden" class="form-control" name="id" id="edit_id"
                            value="{{ old('id') }}" required>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="name" value="{{ $item->name }}" required>
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
