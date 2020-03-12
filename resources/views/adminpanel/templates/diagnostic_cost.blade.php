<input type="number" id="dcost" name="diagnostics_cost[]" placeholder="Diagnostic Cost" class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }} qty1" value="{{$result->cost}}" required>
<input type="hidden" name="diagnostics_appointment_from[]" value="0">
<input type="hidden"  class="qty1" value="0">
