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
                        Nuevo documento
                    </div>
                    <div class="prism-toggle">
                        <a href="{{ url('documento') }}"><button class="btn btn-primary"><i
                                    class="bi bi-arrow-90deg-left"></i></button></a>
                    </div>
                </div>

                <form method="POST" action="{{ route('documento.store') }}">
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
                                <input type="text" class="form-control" name="doc_number" value="{{ old('doc_number') }}" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Justificación:</label>
                                <input type="text" class="form-control" name="justification" value="{{ old('justification') }}" required>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Tipo Documento</label>
                                <select class="form-select" name="document_types_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($tiposDocumento as $tipo)
                                        <option value="{{ $tipo->id }}" {{ old('document_types_id') == $tipo->id ? 'selected' : '' }}>
                                            {{ $tipo->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Bodega</label>
                                <select class="form-select" name="warehouses_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($bodegas as $bodega)
                                        <option value="{{ $bodega->id }}" {{ old('warehouses_id') == $bodega->id ? 'selected' : '' }}>
                                            {{ $bodega->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Producto</label>
                                <select class="form-control select2" name="products_id" required>
                                    <option value="">Seleccione</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}" {{ old('products_id') == $producto->id ? 'selected' : '' }}>
                                            {{ $producto->sku }} - {{ $producto->description }} - {{ $producto->color }}
                                            ({{ $producto->marca->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                <label for="input-label" class="form-label">Cantidad:</label>
                                <input type="number" step="any" class="form-control" name="quantity" min="1"
                                    value="{{ old('quantity') }}" required>
                            </div>
                        </div>


                    </div>

                    <div class="card-footer" style="text-align: right">
                        <button class="btn btn-primary">Aceptar</button>
                    </div>
                </form>

            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            expandMenuAndHighlightOption('inventarioMenu', 'documentoOption');
            // Inicializar todos los select con clase select2
            $('.select2').select2({
                placeholder: "Seleccione",
                allowClear: true,
                width: '100%'
            });

        });
    </script>


@endsection
