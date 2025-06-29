@extends('menu')
@section('content')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



    <div class="row">


        <div class="col-xl-12">
            <div class="row">
                <div class="col-xxl-3 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="text-muted d-block mb-1">Sector Público</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($data['totalSalesPublico'], 2) }}
                                    </h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-primary2">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="d-block text-muted mb-1">Deuda sector Privado</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($data['totalSalesPrivado'], 2) }}</h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-info">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="text-muted d-block mb-1">Total deuda</span>
                                    <h4 class="fw-medium mb-0">${{ number_format($data['totalVentas'], 2) }}</h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-success">
                                        <i class="ti ti-currency-dollar fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-xl-6">
                    <div class="card custom-card overflow-hidden main-content-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div>
                                    <span class="text-muted d-block mb-1">Clientes Atendidos</span>
                                    <h4 class="fw-medium mb-0">{{ $data['clientesAtendidos'] }}</h4>
                                </div>
                                <div class="lh-1">
                                    <span class="avatar avatar-md avatar-rounded bg-warning">
                                        <i class="ti ti-user fs-5"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>


        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">

                    <div id="container" style="width: 100%; height: 500px; margin: 0 auto;"></div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const categories = @json($categories);
        const values = @json($values);
        const valuesPrivado = @json($valuesPrivado);

        // Ambas series usan el mismo array de valores (values)
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Ventas por Mes - Comparación'
            },
            xAxis: {
                categories: categories,
                title: {
                    text: 'Mes'
                }
            },
            yAxis: {
                title: {
                    text: 'Cantidad'
                }
            },
            legend: {
                enabled: true
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return '$' + Highcharts.numberFormat(this.y, 2, '.', ',');
                        }
                    }
                }
            },
            tooltip: {
                pointFormat: 'Total: <b>{point.y:.2f}</b>'
            },
            series: [{
                name: 'Sector público',
                data: values
            }, {
                name: 'Sector privado',
                data: valuesPrivado
            }],
            exporting: {
                enabled: true
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            }
        });
    </script>



    <!-- End:: row-1 -->
@endsection
