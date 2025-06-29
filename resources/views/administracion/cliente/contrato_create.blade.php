@extends ('menu')
@section('content')
    <!-- Toastr CSS -->
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('assets/libs/toast/toastr.min.js') }}"></script>


    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- JS de Select2 -->
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>

    <style>
        .select2-container--default .select2-selection--single {
            height: 38px;
            padding: 5px;
            border: 1px solid #ced4da;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }
    </style>




    <!-- Start:: row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        NUEVO CONTRATO - {{ $cliente->name }} {{ $cliente->lastname }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('cliente') }}/{{ $cliente->id }}"> <button class="btn btn-primary"
                                data-bs-toggle="modal" data-bs-target="#modal-create"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>
                <div class="card-body">

                    <div id="error-container" style="display: none;">
                        <div class="alert alert-danger">
                            <ul id="error-list"></ul>
                        </div>
                    </div>


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


                    <form method="POST" action="{{ route('cliente.contrato.store', $cliente->id) }}" id="form-create">
                        @csrf
                        <div class="card-body">
                            <div class="row gy-4">
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Número</label>
                                    <input type="text" class="form-control" name="number" value="{{ old('number') }}"
                                        required>
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}"
                                        required>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Tipo pago</label>
                                    <select name="payment_type" class="form-select">
                                        <option value="CONTADO">Contado</option>
                                        <option value="CREDITO">Crédito</option>
                                    </select>
                                </div>


                                <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Plazo</label>
                                    <input type="number" step="1" class="form-control" name="term" id="term"
                                        value="{{ old('term') }}" required>
                                </div>



                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Bodega</label>
                                    <select name="warehouses_id" class="form-select" onchange="getProductos(this.value)"
                                        required>
                                        <option value="">Seleccione</option>
                                        @foreach ($bodegas as $bodega)
                                            <option value="{{ $bodega->id }}">{{ $bodega->name }}</option>
                                        @endforeach

                                    </select>
                                </div>





                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Producto</label>
                                    <div style="margin-top: -8px">
                                        <select name="products_id" id="products_id" class="form-control select2" required>
                                            <option value="">Seleccione</option>


                                        </select>
                                    </div>

                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Precio</label>
                                    <input type="number" step="0.01" class="form-control" name="price" min="0.01"
                                        value="{{ old('price') }}" required>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Cantidad</label>
                                    <input type="number" step="0.01" class="form-control" name="quantity" min="1"
                                        value="{{ old('quantity') }}" required>
                                </div>

                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Porcentaje descuento</label>
                                    <input type="number" class="form-control" name="discount"
                                        value="{{ old('discount') }}" required>
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Derecho</label>
                                    <input type="text" class="form-control" name="right_eye_graduation" required
                                        value="{{ old('right_eye_graduation') }}">
                                </div>


                                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Izquierdo</label>
                                    <input type="text" class="form-control" name="left_eye_graduation" required
                                        value="{{ old('left_eye_graduation') }}">
                                </div>

                            </div>



                        </div>

                        <div class="card-footer" style="text-align: right">
                            <button type="button" onclick="validar({{ $cliente->id }})"
                                class="btn btn-primary">Agregar</button>
                        </div>

                    </form>


                </div>

            </div>
        </div>

    </div>


    <!-- Activar DataTable -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('ventasMenu', 'clienteOption');

            $('.select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%'
            });

        });

        function getProductos(id) {

            fetch(`{{ url('producto/get_productos') }}/${id}`, {
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
                        //console.log()
                        const productos = data.data;

                        var _select = '<option value="">Seleccione</option>'
                        for (var i = 0; i < productos.length; i++)
                            _select += '<option value="' + productos[i].id + '"  >' + productos[i].text +
                            '</option>';

                        $("#products_id").html(_select);

                    } else {
                        var _select = '<option value="">Seleccione</option>'
                        $("#products_id").html(_select);

                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    var _select = '<option value="">Seleccione</option>'
                    $("#products_id").html(_select);
                    //alert(error.message);
                });
        }

        async function validar(id) {
            const form = document.getElementById('form-create');
            const formData = new FormData(form);

            // Limpiar errores anteriores
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');
            errorList.innerHTML = '';
            errorContainer.style.display = 'none';

            try {
                const response = await fetch(`{{ url('cliente/validar_contrato_store') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Ocultar errores si los hubiera
                    errorContainer.style.display = 'none';
                    //alert('Validación exitosa. Puedes proceder a guardar.');
                    form.submit();
                } else {
                    // Mostrar errores
                    for (const campo in data.errors) {
                        const li = document.createElement('li');
                        li.textContent = data.errors[campo][0];
                        errorList.appendChild(li);
                    }
                    errorContainer.style.display = 'block';
                }

            } catch (error) {
                alert('Error al validar los datos. Intente nuevamente.');
                console.error(error);
            }
        }
    </script>
@endsection
