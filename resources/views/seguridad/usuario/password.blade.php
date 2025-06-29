<div class="modal fade" id="modal-change-password-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Modificar contraseña</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ url('seguridad/user/update_password') }}/{{ $item->id }}">
                @csrf
                <div class="modal-body">


                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Password:</label>
                            <input type="text" class="form-control" name="password" value="{{ old('password') }}"
                                required>
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
