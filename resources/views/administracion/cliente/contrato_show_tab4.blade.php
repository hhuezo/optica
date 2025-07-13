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
                            data-bs-target="#modal-delete-{{ $abono->id }}"><i class="bi bi-trash"></i></button>
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
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-abono"><i
                            class="bi bi-plus"></i></button>
                @endif
            </td>
        </tr>

    </tbody>
</table>

<div class="card-footer d-flex justify-content-between">
    <button type="button" class="btn btn-primary" onclick="activarPestana('services-style8-tab-pane')">
        Anterior
    </button>


</div>
