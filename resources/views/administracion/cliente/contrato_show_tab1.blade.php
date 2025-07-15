   <form method="POST" action="{{ route('cliente.contrato.update', $contrato->id) }}">
       @csrf
       @method('PUT')
       <div class="card-body">
           <div class="row gy-4">

               <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                   <label class="form-label">Fecha</label>
                   <input type="date" class="form-control" name="date" value="{{ old('date', $contrato->date) }}"
                       required>
               </div>

               <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                   <label for="input-label" class="form-label">Número</label>
                   <input type="text" class="form-control" name="number" value="{{ $contrato->number }}" required>
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
                       <option value="CONTADO" {{ $contrato->payment_type == 'CONTADO' ? 'selected' : '' }}>Contado
                       </option>
                       <option value="CREDITO" {{ $contrato->payment_type == 'CREDITO' ? 'selected' : '' }}>Crédito
                       </option>
                   </select>
               </div>

               <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                   <label for="input-label" class="form-label">Plazo</label>
                   <input type="number" step="1" class="form-control" name="term" id="term"
                       value="{{ $contrato->term }}">
               </div>


               <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                   <label for="input-label" class="form-label">Servicio para</label>
                   <input type="text" class="form-control" name="service_for" value="{{ $contrato->service_for }}"
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
                           value="{{ number_format($abonos + $contrato->advance, 2) }}">
                   </div>


                   <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                       <label for="input-label" class="form-label">Saldo</label>

                       <input type="text" class="form-control" readonly
                           value="{{ number_format($contrato->amount - $abonos - $contrato->advance, 2) }}">
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

           <button type="button" class="btn btn-primary" onclick="activarPestana('about-style8-tab-pane')">
               Siguiente
           </button>
       </div>
   </form>
