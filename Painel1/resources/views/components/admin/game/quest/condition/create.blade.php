<form>
  <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
      <div class="row">
          <div class="d-flex flex-column mb-5 fv-row col-12">
              <label class="form-label">🏷️ Título</label>
              <input type="text" class="form-control form-control-sm form-control-solid"
                  placeholder="" step="1" name="CondictionTitle" />
          </div>
          <div class="d-flex flex-column mb-5 fv-row col-12">
              <label class="form-label">💾 Tipo</label>
              <select class="form-select form-select-sm form-select-solid" data-control="select2" name="CondictionType"
                  data-dropdown-parent="#md_condition_create" placeholder="Selecione o tipo">
                  @foreach ($questConditions as $key => $condition)
                      <option value="{{ $key }}">
                        [{{ $key }}] - {!! $condition['Name'] !!}
                      </option>
                  @endforeach
              </select>
          </div>
      </div>
      <div class="row">
          <div class="mb-5 fv-row col-6">
              <label class="form-label" id="para1">❓ Para1</label>
              <input type="number" class="form-control form-control-sm form-control-solid"
                  placeholder="" step="1" name="Para1" value="" />
          </div>
          <div class="mb-5 fv-row col-6">
              <label class="form-label" id="para2">❓ Para2</label>
              <input type="number" class="form-control form-control-sm form-control-solid"
                  placeholder="" step="1" name="Para2" value="" />
          </div>
      </div>
      <div class="d-flex flex-stack mb-5">
          <div class="me-5">
              <label class="fs-6 fw-bold form-label">❓ isOpitional</label>
              <div class="fs-7 fw-bold text-muted">
                  Se marcado a condição será opicional
              </div>
          </div>
          <label class="form-check form-switch form-check-custom form-check-solid">
              <input class="form-check-input h-20px w-30px" type="checkbox" name="isOpitional"
                  value="1" />
              <span class="form-check-label fw-bold text-muted"></span>
          </label>
      </div>
      <div class="text-center">
          <button type="button" onclick="condition.create()"
              id="btn_condition_create" class="btn btn-sm btn-light-primary w-100">
              <span class="indicator-label">Adicionar</span>
              <span class="indicator-progress">
                  adicionando...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
          </button>
      </div>
  </div>
</form>
