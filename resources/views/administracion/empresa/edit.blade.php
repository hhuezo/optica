<div class="modal fade" id="modal-edit-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar Empresa</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('empresa.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="name" value="{{ $item->name }}"
                                required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">NIT</label>
                            <input type="text" class="form-control" name="nit" id="edit_nit"
                                value="{{ $item->nit }}" required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Contacto</label>
                            <input type="text" class="form-control" name="contact" value="{{ $item->contact }}"
                                required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="phone" value="{{ $item->phone }}"
                                required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="address" value="{{ $item->address }}"
                                required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Sector</label>
                            <select class="form-select" name="company_category_id">
                                @foreach ($sectores as $sector)
                                    <option value="{{ $sector->id }}"
                                        {{ $item->company_category_id == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Estado</label>
                            <select class="form-select" name="statuses_id">
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
