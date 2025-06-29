<div class="modal fade" id="modal-procesar" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Procesar documento</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('documento.procesar_ingreso', $documento->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="row gy-4">
                        <p class="modal-title">¿Está seguro que quiere procesar el documento?</p>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Procesar</button>
                </div>

            </form>
        </div>

        </form>
    </div>
</div>
