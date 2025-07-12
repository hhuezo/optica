@extends ('menu')
@section('content')
    <!-- Toastr CSS -->
    <link href="{{ asset('assets/libs/toast/toastr.min.css') }}" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Toastr JS -->
    <script src="{{ asset('assets/libs/toast/toastr.min.js') }}"></script>


    <!-- Start:: row-1 -->
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    NUEVO CONTRATO - {{ $cliente->name }} {{ $cliente->lastname }}
                </div>

                <div class="prism-toggle">
                    <a href="{{ url('cliente') }}/{{ $cliente->id }}"> <button class="btn btn-primary"><i
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
                        <button class="nav-link active" id="home-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#home-style8-tab-pane" type="button" role="tab"
                            aria-controls="home-style8-tab-pane" aria-selected="true">Contrato</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="about-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#about-style8-tab-pane" type="button" role="tab"
                            aria-controls="about-style8-tab-pane" aria-selected="false" disabled>Productos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="services-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#services-style8-tab-pane" type="button" role="tab"
                            aria-controls="services-style8-tab-pane" aria-selected="false" disabled>Asesores</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="services-style8-tab" data-bs-toggle="tab"
                            data-bs-target="#services-style8-tab-pane" type="button" role="tab"
                            aria-controls="services-style8-tab-pane" aria-selected="false" disabled>Abonos</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent3">
                    <div class="tab-pane show active overflow-hidden" id="home-style8-tab-pane" role="tabpanel"
                        aria-labelledby="home-style8-tab" tabindex="0">



                        <form method="POST" action="{{ route('cliente.contrato.store', $cliente->id) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row gy-4">

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ old('date', date('Y-m-d')) }}" required>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Número</label>
                                        <input type="text" class="form-control" name="number"
                                            value="{{ old('number') }}" required>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Bodega</label>
                                        <select name="warehouses_id" class="form-select" required>
                                            <option value="">Seleccione</option>
                                            @foreach ($bodegas as $bodega)
                                                <option value="{{ $bodega->id }}"
                                                    {{ old('warehouses_id') == $bodega->id ? 'selected' : '' }}>
                                                    {{ $bodega->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Tipo pago</label>
                                        <select name="payment_type" class="form-select">
                                            <option value="CONTADO"
                                                {{ old('payment_type') == 'CONTADO' ? 'selected' : '' }}>Contado</option>
                                            <option value="CREDITO"
                                                {{ old('payment_type') == 'CREDITO' ? 'selected' : '' }}>Crédito</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Plazo</label>
                                        <input type="number" step="1" class="form-control" name="term"
                                            value="{{ old('term') }}">
                                    </div>



                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Servicio para</label>
                                        <input type="text" class="form-control" name="service_for"
                                            value="{{ old('service_for') }}"
                                            onblur="this.value = this.value.toUpperCase()">
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Diagnóstico</label>
                                        <textarea class="form-control" name="diagnostic">{{ old('diagnostic') }}</textarea>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                                        <label for="input-label" class="form-label">Observaciones</label>
                                        <textarea class="form-control" name="observation">{{ old('observation') }}</textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer" style="text-align: right">
                                <button type="submit" class="btn btn-primary">Siguiente</button>
                            </div>
                        </form>




                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            expandMenuAndHighlightOption('ventasMenu', 'clienteOption');
        });
    </script>
@endsection
