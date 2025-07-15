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
                        <button class="nav-link  {{ $tab == 4 ? 'active' : '' }}" id="abonos-style8-tab" {{$contrato->statuses_id == 4 ? 'disabled':''}}
                            data-bs-toggle="tab" data-bs-target="#abonos-style8-tab-pane" type="button" role="tab"
                            aria-controls="abonos-style8-tab-pane" aria-selected="false">Abonos</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent3">
                    <div class="tab-pane {{ $tab == 1 ? 'show active' : '' }} overflow-hidden" id="home-style8-tab-pane"
                        role="tabpanel" aria-labelledby="home-style8-tab" tabindex="0">



                        @include('administracion.cliente.contrato_show_tab1')



                    </div>
                    <div class="tab-pane {{ $tab == 2 ? 'show active' : '' }} overflow-hidden" id="about-style8-tab-pane"
                        role="tabpanel" aria-labelledby="about-style8-tab" tabindex="0">


                        @include('administracion.cliente.contrato_show_tab2')



                    </div>
                    <div class="tab-pane {{ $tab == 3 ? 'show active' : '' }} overflow-hidden"
                        id="services-style8-tab-pane" role="tabpanel" aria-labelledby="services-style8-tab" tabindex="0">


                        @include('administracion.cliente.contrato_show_tab3')


                    </div>
                    <div class="tab-pane {{ $tab == 4 ? 'show active' : '' }} overflow-hidden" id="abonos-style8-tab-pane"
                        role="tabpanel" aria-labelledby="abonos-style8-tab" tabindex="0">

                        @include('administracion.cliente.contrato_show_tab4')

                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('administracion.cliente.contrato_show_modales')


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

            document.getElementById('advance_modal').value = advance;

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
