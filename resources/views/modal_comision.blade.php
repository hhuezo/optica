<div class="modal fade" id="modal-comision-{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Reporte comisión</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="GET" action="{{ url('reportes/comisiones') }}/{{ $item->id }}">
                @csrf
                <div class="modal-body">


                    <div class="row gy-4">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="name"
                                value="{{ $item->name }} {{ $item->last_name }}" required>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Fecha inicio:</label>
                            <input type="date" class="form-control" name="fechaInicio" value="" required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Fecha final:</label>
                            <input type="date" class="form-control" name="fechaFinal" value="" required>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                              <label class="switch">
                                <input type="checkbox" name="exportar">
                                <span class="slider round"></span>
                            </label>&nbsp;
                            <label for="input-label" class="form-label">Exportar excel</label>

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
