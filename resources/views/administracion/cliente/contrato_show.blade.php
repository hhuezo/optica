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



    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    CONTRATO - {{ $contrato->cliente->name ?? '' }} {{ $contrato->cliente->lastname ?? '' }}
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

                <ul class="nav nav-tabs mb-3 tab-style-8 scaleX" id="myTab4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab == 1 ? 'active' : '' }}" id="home-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#home-style8-tab-pane" type="button" role="tab"
                            aria-controls="home-style8-tab-pane" aria-selected="true">Contrato</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $tab == 2 ? 'active' : '' }}" id="about-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#about-style8-tab-pane" type="button" role="tab"
                            aria-controls="about-style8-tab-pane" aria-selected="false">Asesores</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  {{ $tab == 3 ? 'active' : '' }}" id="services-style8-tab"
                            data-bs-toggle="tab" data-bs-target="#services-style8-tab-pane" type="button" role="tab"
                            aria-controls="services-style8-tab-pane" aria-selected="false">Productos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  {{ $tab == 4 ? 'active' : '' }}" id="abonos-style8-tab"
                            data-bs-toggle="tab" data-bs-target="#abonos-style8-tab-pane" type="button" role="tab"
                            aria-controls="abonos-style8-tab-pane" aria-selected="false">Abonos</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent3">
                    <div class="tab-pane {{ $tab == 1 ? 'show active' : '' }} overflow-hidden" id="home-style8-tab-pane"
                        role="tabpanel" aria-labelledby="home-style8-tab" tabindex="0">



                        <form method="POST" action="{{ route('cliente.contrato.update', $contrato->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row gy-4">

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ old('date', $contrato->date) }}" required>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Número</label>
                                        <input type="text" class="form-control" name="number"
                                            value="{{ $contrato->number }}" required>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Bodega</label>
                                        <select name="warehouses_id" class="form-select" required>
                                            <option value="">Seleccione</option>
                                            @foreach ($bodegas as $bodega)
                                                <option value="{{ $bodega->id }}"
                                                    {{ $contrato->warehouses_id == $bodega->id ? 'selected' : '' }}>
                                                    {{ $bodega->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Tipo pago</label>
                                        <select name="payment_type" class="form-select">
                                            <option value="CONTADO"
                                                {{ $contrato->payment_type == 'CONTADO' ? 'selected' : '' }}>Contado
                                            </option>
                                            <option value="CREDITO"
                                                {{ $contrato->payment_type == 'CREDITO' ? 'selected' : '' }}>Crédito
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Plazo</label>
                                        <input type="number" step="1" class="form-control" name="term"
                                            id="term" value="{{ $contrato->term }}">
                                    </div>


                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Servicio para</label>
                                        <input type="text" class="form-control" name="service_for"
                                            value="{{ $contrato->service_for }}"
                                            onblur="this.value = this.value.toUpperCase()">
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

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Diagnóstico</label>
                                        <textarea class="form-control" name="diagnostic">{{ $contrato->diagnostic }}</textarea>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Observaciones</label>
                                        <textarea class="form-control" name="observation">{{ $contrato->observation }}</textarea>
                                    </div>

                                </div>













                            </div>

                            <div class="card-footer" style="text-align: right">
                                @if ($contrato->statuses_id == 4)
                                    <button type="submit" class="btn btn-primary">Siguiente</button>
                                @else
                                    <button type="button" class="btn btn-primary"
                                        onclick="activarPestana('about-style8-tab-pane')">
                                        Siguiente
                                    </button>
                                @endif
                            </div>
                        </form>




                    </div>
                    <div class="tab-pane {{ $tab == 2 ? 'show active' : '' }} overflow-hidden" id="about-style8-tab-pane"
                        role="tabpanel" aria-labelledby="about-style8-tab" tabindex="0">

                        <ul class="list-unstyled mb-0 analytics-visitors-countries">
                            <div class="row">
                                @foreach ($users as $user)
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <li class="mb-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div>
                                                    <label class="switch">
                                                        <input type="checkbox"
                                                            {{ $contrato->statuses_id != 4 ? 'disabled' : '' }}
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


                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-primary"
                                onclick="activarPestana('home-style8-tab-pane')">
                                Anterior
                            </button>

                            <button type="button" class="btn btn-primary"
                                onclick="activarPestana('services-style8-tab-pane')">
                                Siguiente
                            </button>
                        </div>




                    </div>
                    <div class="tab-pane {{ $tab == 3 ? 'show active' : '' }} overflow-hidden"
                        id="services-style8-tab-pane" role="tabpanel" aria-labelledby="services-style8-tab"
                        tabindex="0">


                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Derecho</th>
                                    <th>Izquierdo</th>
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
                                        <td>
                                            {!! !empty($detalle->right_eye_sphere) ? '<strong>Esfera:</strong> ' . $detalle->right_eye_sphere : '' !!}
                                            {!! !empty($detalle->right_eye_cylinder) ? ' <strong>Cilindro:</strong> ' . $detalle->right_eye_cylinder : '' !!}
                                            {!! !empty($detalle->right_eye_axis) ? ' <strong>Eje:</strong> ' . $detalle->right_eye_axis : '' !!}
                                            {!! !empty($detalle->right_eye_graduation) ? ' <strong>Adición:</strong> ' . $detalle->right_eye_graduation : '' !!}

                                        </td>

                                        <td>
                                            {!! !empty($detalle->left_eye_sphere) ? '<strong>Esfera:</strong> ' . $detalle->left_eye_sphere : '' !!}
                                            {!! !empty($detalle->left_eye_cylinder) ? ' <strong>Cilindro:</strong> ' . $detalle->left_eye_cylinder : '' !!}
                                            {!! !empty($detalle->left_eye_axis) ? ' <strong>Eje:</strong> ' . $detalle->left_eye_axis : '' !!}
                                            {!! !empty($detalle->left_eye_graduation) ? ' <strong>Adición:</strong> ' . $detalle->left_eye_graduation : '' !!}

                                        </td>

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

                                @if ($contrato->detalles->count() > 0 && $contrato->statuses_id == 4)
                                    <tr>
                                        <td colspan="2" style="text-align: right">
                                            <strong>Adelanto $</strong>
                                        </td>
                                        <td><input type="number" name="advance" id="advance" class="form-control"
                                                step="0.01" onchange="calcMonthlyPayment()"
                                                value="{{ old('advance') }}"></td>
                                        <td colspan="4" style="text-align: right"><strong>Cuota</strong></td>
                                        <td style="text-align: right">
                                            <strong><span id="span_monthly_payment"></span></strong>
                                        </td>
                                        <td style="text-align: center">
                                        </td>
                                    </tr>
                                @else
                                    <input type="hidden" id="advance" class="form-control" value="">
                                    <span id="span_monthly_payment"></span>
                                @endif


                                @if ($contrato->statuses_id == 4)
                                    <tr>
                                        <td colspan="9" style="text-align: center"><button class="btn btn-success"
                                                data-bs-toggle="modal" data-bs-target="#modal-create">+</button></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>



                        @if ($contrato->statuses_id == 4)
                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-primary"
                                    onclick="activarPestana('about-style8-tab-pane')">
                                    Anterior
                                </button>


                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modal-procesar">Procesar</button>


                            </div>
                        @else
                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-primary"
                                    onclick="activarPestana('about-style8-tab-pane')">
                                    Anterior
                                </button>

                                <button type="button" class="btn btn-primary"
                                    onclick="activarPestana('abonos-style8-tab-pane')">
                                    Siguiente
                                </button>
                            </div>
                        @endif



                        </form>



                    </div>
                    <div class="tab-pane {{ $tab == 4 ? 'show active' : '' }} overflow-hidden"
                        id="abonos-style8-tab-pane" role="tabpanel" aria-labelledby="abonos-style8-tab" tabindex="0">


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

                        <div class="card-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-primary"
                                onclick="activarPestana('services-style8-tab-pane')">
                                Anterior
                            </button>


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
                                <input type="number" step="0.01" class="form-control" name="quantity"
                                    min="1" value="{{ old('quantity') }}" required>
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Porcentaje descuento</label>
                                <input type="number" class="form-control" name="discount"
                                    value="{{ old('discount') }}">
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
                                <input type="text" class="form-control" name="right_eye_graduation"
                                    value="{{ old('right_eye_graduation') }}">
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
                                <input type="text" class="form-control" name="left_eye_graduation"
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
                            <input type="hidden" id="total_amount" value="{{ $total }}">
                            <input type="hidden" name="monthly_payment" id="monthly_payment" class="form-control">

                            <h6>¿Desea procesar el servicio?</h6>
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
                                <label class="form-label">Saldo</label>
                                <input type="text" class="form-control" value="{{ number_format($contrato->amount - $abonos, 2) }}" readonly>
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

            calcMonthlyPayment();

        });


        function calcMonthlyPayment() {
            const total_amount = parseFloat(document.getElementById('total_amount').value) || 0.00;
            const advanceInput = document.getElementById('advance');
            const advance = advanceInput ? parseFloat(advanceInput.value) || 0.00 : 0.00;
            const term = parseFloat(document.getElementById('term').value) || 0.00;

            console.log("term ", term);
            if (term > 0) {
                const monthly = ((total_amount - advance) / term).toFixed(2);
                document.getElementById('monthly_payment').value = monthly;
                document.getElementById('span_monthly_payment').textContent = "$" + monthly;
            }
        }


        function activarPestana(targetId) {
            var tabTriggerEl = document.querySelector('[data-bs-target="#' + targetId + '"]');

            if (tabTriggerEl) {
                var tab = new bootstrap.Tab(tabTriggerEl);
                tab.show();
            }
        }
    </script>


@endsection
