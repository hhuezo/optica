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
                        Salida
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('documento') }}"><button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>

                <form method="POST" action="{{ route('documento.detalle.store', $documento->id) }}">
                    @csrf
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



                        <div class="row gy-4">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Documento:</label>
                                <input type="text" class="form-control" name="doc_number"
                                    value="{{ $documento->doc_number }}" readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Justificación:</label>
                                <input type="text" class="form-control" name="justification"
                                    value="{{ $documento->justification }}" readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Tipo Documento</label>
                                <input type="text" class="form-control"
                                    value="{{ $documento->tipoDocumento->name ?? '' }}" readonly>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Bodega</label>
                                <input type="text" class="form-control" value="{{ $documento->bodega->name ?? '' }}"
                                    readonly>
                            </div>
                        </div>


                    </div>

                    <div class="card-footer" style="text-align: right">
                        @if ($documento->statuses_id == 7)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#modal-procesar">Procesar</button>
                        @endif

                    </div>
                </form>

            </div>

            @include('inventario.documento.salida_procesar')

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Color</th>
                            <th>Marca</th>
                            <th>Cantidad</th>
                            @if ($documento->statuses_id == 7)
                                <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @foreach ($documento->detalles as $detalle)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $detalle->producto->sku ?? '' }}</td>
                                <td>{{ $detalle->producto->description ?? '' }}</td>
                                <td>{{ $detalle->producto->color ?? '' }}</td>
                                <td>{{ $detalle->producto->marca->name ?? '' }}</td>
                                <td>{{ $detalle->quantity }}</td>
                                @if ($documento->statuses_id == 7)
                                    <td>
                                        <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#detalle-delete-{{ $detalle->id }}"><i
                                                class="bi bi-trash-fill"></i></a>

                                    </td>
                                @endif
                            </tr>
                            @php($i++)
                            @include('inventario.documento.delete_detalle')
                        @endforeach

                        @if ($documento->statuses_id == 7)
                            <tr>
                                <td colspan="7" style="text-align: center"><button class="btn btn-success"
                                        data-bs-toggle="modal" data-bs-target="#modal-create">+</button></td>
                            </tr>
                        @endif

                    </tbody>
                </table>
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
                <form method="POST" action="{{ url('documento/detalle_store') }}">
                    @csrf
                    <div class="modal-body">


                        <div class="row gy-4">

                            <input type="hidden" class="form-control" name="documents_id" value="{{ $documento->id }}">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Producto</label>
                                <select class="form-control select2" name="products_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                            {{ old('products_id') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->sku }} - {{ $producto->description }} - {{ $producto->color }}
                                            ({{ $producto->marca->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Cantidad:</label>
                                <input type="number" step="any" class="form-control" name="quantity" min="1"
                                    value="{{ old('quantity') }}" required>
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


    <script>
        $(document).ready(function() {
            expandMenuAndHighlightOption('inventarioMenu', 'documentoOption');
            $('#modal-create .select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modal-create') // <-- Aquí está la clave
            });
        });
    </script>



@endsection
