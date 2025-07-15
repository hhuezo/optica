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
           @foreach ($contrato->detalles as $detalle)
               @php($subTotal = $detalle->quantity * $detalle->price * (1 - $detalle->discount / 100) ?? 0.0)
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
                               data-bs-target="#modal-delete-{{ $detalle->id }}"><i class="bi bi-trash"></i></button>
                       @endif
                   </td>
               </tr>
               @include('administracion.cliente.detalle_delete')
               @php($i++)
           @endforeach

           <tr>
               <td colspan="7" style="text-align: right"><strong>Total</strong></td>
               <td style="text-align: right">
                   <strong>${{ number_format($totalAmount, 2) }}
               </td>
               <td style="text-align: center">
                   <input type="hidden" id="total_amount" value="{{ $totalAmount }}">
               </td>
           </tr>

           @if ($contrato->detalles->count() > 0 && $contrato->statuses_id == 4)
               <tr>
                   <td colspan="2" style="text-align: right">
                       <strong>Adelanto $</strong>
                   </td>
                   <td><input type="number" name="advance" id="advance" class="form-control" step="0.01"
                           onInput="calcMonthlyPayment()" value="{{ old('advance') }}"></td>
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
                   <td colspan="9" style="text-align: center"><button class="btn btn-success" data-bs-toggle="modal"
                           data-bs-target="#modal-create">+</button></td>
               </tr>
           @endif
       </tbody>
   </table>



   @if ($contrato->statuses_id == 4)
       <div class="card-footer d-flex justify-content-between">
           <button type="button" class="btn btn-primary" onclick="activarPestana('about-style8-tab-pane')">
               Anterior
           </button>


           <button type="button" class="btn btn-success" data-bs-toggle="modal"
               data-bs-target="#modal-procesar">Procesar</button>


       </div>
   @else
       <div class="card-footer d-flex justify-content-between">
           <button type="button" class="btn btn-primary" onclick="activarPestana('about-style8-tab-pane')">
               Anterior
           </button>

           <button type="button" class="btn btn-primary" onclick="activarPestana('abonos-style8-tab-pane')">
               Siguiente
           </button>
       </div>
   @endif
