<div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Agregar producto</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('cliente.contrato_detalle.store', $contrato->id) }}">
                @csrf
                <div class="modal-body">


                    <div class="row gy-4">



                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Producto</label>
                            <div style="margin-top: -8px">
                                <select name="products_id" id="products_id" class="form-control select2" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                            {{ old('products_id') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->text }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Precio</label>
                            <input type="number" step="0.01" class="form-control" name="price" min="0.01"
                                value="{{ old('price') }}" required>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Cantidad</label>
                            <input type="number" step="0.01" class="form-control" name="quantity" min="1"
                                value="{{ old('quantity') }}" required>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Porcentaje descuento</label>
                            <input type="number" class="form-control" name="discount" value="{{ old('discount') }}">
                        </div>

                        <div class="modal-header">
                            <h6 class="modal-title">Ojo derecho</h6>
                        </div>


                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Esfera</label>
                            <input type="text" class="form-control" name="right_eye_sphere"
                                value="{{ old('right_eye_sphere') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Cilindo</label>
                            <input type="text" class="form-control" name="right_eye_cylinder"
                                value="{{ old('right_eye_cylinder') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Eje</label>
                            <input type="text" class="form-control" name="right_eye_axis"
                                value="{{ old('right_eye_axis') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Adición</label>
                            <input type="text" class="form-control" name="right_eye_addition"
                                value="{{ old('right_eye_addition') }}">
                        </div>


                        <div class="modal-header">
                            <h6 class="modal-title">Ojo izquierdo</h6>
                        </div>


                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Esfera</label>
                            <input type="text" class="form-control" name="left_eye_sphere"
                                value="{{ old('left_eye_sphere') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Cilindo</label>
                            <input type="text" class="form-control" name="left_eye_cylinder"
                                value="{{ old('left_eye_cylinder') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Eje</label>
                            <input type="text" class="form-control" name="left_eye_axis"
                                value="{{ old('left_eye_axis') }}">
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                            <label for="input-label" class="form-label">Adición</label>
                            <input type="text" class="form-control" name="left_eye_addition"
                                value="{{ old('left_eye_addition') }}">
                        </div>


                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>

            </form>
        </div>

        </form>
    </div>
</div>


<div class="modal fade" id="modal-procesar" tabindex="-1" aria-labelledby="exampleModalLgLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Procesar</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ url('cliente/contrato_procesar') }}/{{ $contrato->id }}">
                @csrf
                <div class="modal-body">
                    <div class="row gy-4">
                        <input type="hidden" id="total_amount" value="{{ $totalAmount }}">
                        <input type="hidden" name="advance" id="advance_modal" class="form-control">
                        <input type="hidden" name="monthly_payment" id="monthly_payment" class="form-control">

                        <h6>¿Desea procesar el servicio?</h6>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Procesar</button>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-abono" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLgLabel">Nuevo abono</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ url('cliente/contrato_abono') }}/{{ $contrato->id }}">
                @csrf
                <div class="modal-body">
                    <div class="row gy-4">
                        <div class="col-12">
                            <label class="form-label">Saldo</label>
                            <input type="text" class="form-control"
                                value="{{ number_format($contrato->amount - $abonos - $contrato->advance, 2) }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Fecha</label>
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                value="{{ old('date') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Número</label>
                            <input type="text" class="form-control" name="number" value="{{ old('number') }}"
                                required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Cantidad</label>
                            <input type="number" class="form-control" name="amount"
                                value="{{ $contrato->monthly_payment }}" min="0.01" step="any" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Aplicar abono</button>
                </div>

            </form>
        </div>

        </form>
    </div>
</div>
