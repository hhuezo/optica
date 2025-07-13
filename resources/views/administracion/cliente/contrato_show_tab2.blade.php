<ul class="list-unstyled mb-0 analytics-visitors-countries">
    <div class="row">
        @foreach ($users as $user)
            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                <li class="mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <label class="switch">
                                <input type="checkbox" {{ $contrato->statuses_id != 4 ? 'disabled' : '' }}
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
    <button type="button" class="btn btn-primary" onclick="activarPestana('home-style8-tab-pane')">
        Anterior
    </button>

    <button type="button" class="btn btn-primary" onclick="activarPestana('services-style8-tab-pane')">
        Siguiente
    </button>
</div>
