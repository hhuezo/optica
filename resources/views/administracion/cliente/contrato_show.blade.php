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
                        NUEVO CONTRATO - {{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('cliente') }}/{{ $contrato->clients_id }}"> <button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
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



                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Número</label>
                                <input type="text" class="form-control" name="number" value="{{ $contrato->number }}"
                                    readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Fecha</label>
                                <input type="date" class="form-control" name="date" value="{{ $contrato->date }}"
                                    readonly>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Tipo pago</label>

                                <input type="text" class="form-control" name="date"
                                    value="{{ $contrato->payment_type }}" readonly>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Plazo</label>
                                <input type="number" step="1" class="form-control" name="term" id="term"
                                    value="{{ $contrato->term }}" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Bodega</label>

                                <input type="text" class="form-control" name="date"
                                    value="{{ $contrato->bodega->name ?? '' }}" readonly>
                            </div>


                            @if ($contrato->statuses_id == 10 || $contrato->statuses_id == 5)
                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Total</label>

                                    <input type="text" class="form-control" readonly
                                        value="{{ number_format($contrato->amount, 2) }}">
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Abonos</label>

                                    <input type="text" class="form-control" readonly
                                        value="{{ number_format($abonos, 2) }}">
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Saldo</label>

                                    <input type="text" class="form-control" readonly
                                        value="{{ number_format($contrato->amount - $abonos, 2) }}">
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                    <label for="input-label" class="form-label">Cuota</label>

                                    <input type="text" class="form-control" readonly
                                        value="{{ number_format($contrato->monthly_payment, 2) }}">
                                </div>
                            @endif

                        </div>



                    </div>

                </div>
            </div>



            <div class="col-xl-12">
                <div class="card custom-card">
                    {{-- <div class="card-header">
                        <div class="card-title">
                            PRODUCTOS
                        </div>
                    </div> --}}
                    <div class="card-body">
                        <ul class="nav nav-pills justify-content-end nav-style-3 mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 1 ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                                    aria-current="page" href="#home-right" aria-selected="true">Productos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $tab == 2 ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                                    aria-current="page" href="#about-right" aria-selected="true">Asesor/Optometrista</a>
                            </li>
                            @if ($contrato->statuses_id == 10 || $contrato->statuses_id == 5)
                                <li class="nav-item">
                                    <a class="nav-link {{ $tab == 3 ? 'active' : '' }}" data-bs-toggle="tab"
                                        role="tab" aria-current="page" href="#services-right"
                                        aria-selected="true">Abonos</a>
                                </li>
                            @endif

                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                    href="#contacts-right" aria-selected="true">Contacts</a>
                            </li> --}}
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane {{ $tab == 1 ? 'show active' : '' }}  text-muted" id="home-right"
                                role="tabpanel">

                                <table class="table table-bordered table-striped">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Dere.</th>
                                            <th>Izqu.</th>
                                            <th>Precio</th>
                                            <th>Cant.</th>
                                            <th>Desc.</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($i = 1)
                                        @php($total = 0.0)
                                        @foreach ($contrato->detalles as $detalle)
                                            @php($subTotal = $detalle->quantity * $detalle->price * (1 - $detalle->discount / 100) ?? 0.0)
                                            @php($total += $subTotal)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $detalle->producto->sku ?? '' }} -
                                                    {{ $detalle->producto->description ?? '' }}
                                                    - {{ $detalle->producto->color ?? '' }}</td>
                                                <td>{{ $detalle->right_eye_graduation }} </td>
                                                <td>{{ $detalle->left_eye_graduation }} </td>
                                                <td style="text-align: right">${{ $detalle->price }} </td>
                                                <td>{{ $detalle->quantity }} </td>
                                                <td>{{ number_format($detalle->discount, 0) }}%</td>
                                                <td style="text-align: right">
                                                    ${{ number_format($subTotal, 2) }}
                                                </td>
                                                <td style="text-align: center">
                                                    @if ($contrato->statuses_id == 4)
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modal-delete-{{ $detalle->id }}"><i
                                                                class="bi bi-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @include('administracion.cliente.detalle_delete')
                                            @php($i++)
                                        @endforeach

                                        <tr>
                                            <td colspan="7" style="text-align: right"><strong>Total</strong></td>
                                            <td style="text-align: right">
                                                <strong>${{ number_format($total, 2) }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="hidden" id="total_amount" value="{{ $total }}">
                                            </td>
                                        </tr>

                                        @if ($contrato->statuses_id == 4)
                                            <tr>
                                                <td colspan="9" style="text-align: center"><button
                                                        class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modal-create">+</button></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>



                                <div class="card-footer" style="text-align: right">
                                    @if ($contrato->statuses_id == 4)
                                        <button class="btn btn-success" data-bs-toggle="modal"
                                            data-bs-target="#modal-procesar">Procesar</button>
                                    @endif


                                </div>

                            </div>
                            <div class="tab-pane {{ $tab == 2 ? 'show active' : '' }} text-muted" id="about-right"
                                role="tabpanel">

                                <ul class="list-unstyled mb-0 analytics-visitors-countries">
                                    <div class="row">
                                        @foreach ($users as $user)
                                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                                <li class="mb-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div>
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                    onchange="contratoEmpleadoStore({{ $contrato->id }},{{ $user->id }})"
                                                                    {{ in_array($user->id, $usuariosAsignados) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                        <div class="ms-1 flex-fill lh-1">
                                                            <span class="fs-14">{{ $user->name }}
                                                                {{ $user->last_name }}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                        @endforeach
                                    </div>
                                </ul>


                            </div>
                            <div class="tab-pane {{ $tab == 3 ? 'show active' : '' }} text-muted" id="services-right"
                                role="tabpanel">

                                <br>
                                <table class="table table-bordered table-striped">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Número</th>
                                            <th>Fecha</th>
                                            <th>Aplicado</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php($total = 0)
                                        @foreach ($contrato->abonos->sortByDesc('date') as $abono)
                                            <tr>
                                                <td>{{ $abono->number }}</td>
                                                <td>{{ isset($abono->date) ? date('d/m/Y', strtotime($abono->date)) : '' }}
                                                </td>
                                                <td>{{ isset($abono->created_at) ? date('d/m/Y H:i:s', strtotime($abono->created_at)) : '' }}
                                                </td>
                                                <td style="text-align: right">
                                                    {{ !empty($abono->amount) ? '$' . number_format($abono->amount, 2) : '' }}
                                                </td>


                                                <td style="text-align: center">
                                                    @if ($contrato->statuses_id == 10)
                                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                            data-bs-target="#modal-delete-{{ $abono->id }}"><i
                                                                class="bi bi-trash"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php($total += !empty($abono->amount) ? $abono->amount : 0)
                                        @endforeach
                                        <tr>
                                            <td colspan="3" style="text-align: right"><strong>Total</strong></td>
                                            <td style="text-align: right">
                                                <strong>${{ number_format($total, 2) }}
                                            </td>
                                            <td style="text-align: center">
                                                <input type="hidden" id="total_amount" value="{{ $total }}">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="5" style="text-align: center">
                                                @if ($contrato->statuses_id == 10)
                                                    <button class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modal-abono"><i class="bi bi-plus"></i></button>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                            {{-- <div class="tab-pane text-muted" id="contacts-right" role="tabpanel">
                                Why delicious magazines are killing you. Why our world would end if restaurants
                                disappeared. Why restaurants are on crack about restaurants. How restaurants are
                                making the world a better place. 8 great articles about minute meals. Why our
                                world would end if healthy snacks disappeared. Why the world would end without
                                mexican food. The evolution of chef uniforms. How not knowing food processors
                                makes you a rookie. Why whole foods markets beat peanut butter on pancakes.
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>



    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLgLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLgLabel">Agregar producto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('cliente.contrato_detalle.store', $contrato->cliente->id) }}">
                    @csrf
                    <input type="hidden" class="form-control" name="contracts_id" value="{{ $contrato->id }}"
                        required>
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
                                <input type="number" step="0.01" class="form-control" name="quantity"
                                    min="1" value="{{ old('quantity') }}" required>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Porcentaje descuento</label>
                                <input type="number" class="form-control" name="discount"
                                    value="{{ old('discount') }}">
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
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Adelanto</label>
                                <input type="number" class="form-control" name="advance" id="advance" step="0.01"
                                    onchange="calcMonthlyPayment()" value="{{ old('advance') }}">
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Cuota</label>
                                <input type="text" class="form-control" name="monthly_payment" id="monthly_payment"
                                    readonly value="{{ old('monthly_payment') }}">
                            </div>
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




    <!-- Activar DataTable  -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('ventasMenu', 'clienteOption');

            $('#modal-create .select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modal-create')
            });


            const select = document.getElementById('warehouses_id');
            if (select && select.value) {
                // Dispara manualmente el evento onchange con el valor actual
                getProductos(select.value);
            }


            var total_amount = document.getElementById('total_amount').value ?? 0.00;
            var term = document.getElementById('term').value ?? 0.00;

            if (parseFloat(term) > 0) {
                document.getElementById('monthly_payment').value = (parseFloat(total_amount) / parseFloat(term))
                    .toFixed(2);
            }

        });


        function calcMonthlyPayment() {
            var total_amount = document.getElementById('total_amount').value ?? 0.00;
            var advance = document.getElementById('advance').value ?? 0.00;
            var term = document.getElementById('term').value ?? 0.00;

            if (parseFloat(term) > 0) {
                document.getElementById('monthly_payment').value = ((parseFloat(total_amount) - parseFloat(advance)) /
                    parseFloat(term)).toFixed(2);
            }

        }


        function contratoEmpleadoStore(id, empleadoId) {
            fetch(`{{ url('cliente/contrato_empleado_store') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        empleado_id: empleadoId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en el servidor');
                    //console.log(response.error);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        //console.log(data.success);
                        console.log('Empleado asignado correctamente');
                    } else {
                        throw new Error(data.message || 'Acción fallida');
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    alert('Error: ' + error.message);
                });
        }
    </script>


@endsection
