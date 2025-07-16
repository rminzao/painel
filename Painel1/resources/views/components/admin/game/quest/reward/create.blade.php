<form id="version_server" data-require-version="4100">
  <div class="row">
      <label class="form-label">📦 Item</label>
      <select class="form-select form-select-sm form-select-solid" data-dropdown-parent="#md_reward_create"
          data-placeholder="Selecione um item" data-allow-clear="true" name="RewardItemID">
      </select>
  </div>
  <div id="info_area" style="display: none">
      <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
          <div class="row mt-7">
              <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                  <label class="form-label">
                      🥾 Level
                  </label>
                  <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                      data-hide-search="true" data-placeholder="Nível" name="StrengthenLevel">
                      <option></option>
                      <option value="0">Sem level</option>
                      @for ($i = 1; $i <= 12; $i++)
                          <option value="{{ $i }}">Nível {{ $i }}</option>
                      @endfor
                      <option value="13">Avanço 1</option>
                      <option value="14">Avanço 2</option>
                      <option value="15">Avanço 3</option>
                  </select>
              </div>
              <div class="d-flex flex-column mb-5 fv-row col-12" id="valid_area">
                  <label class="form-label">🗓️ Validade</label>
                  <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                      data-hide-search="true" name="RewardItemValid">
                      <option value="0" selected>Permanente</option>
                      <option value="1">1 Dia</option>
                      <option value="3">3 Dias</option>
                      <option value="7">7 Dias</option>
                      <option value="15">15 Dias</option>
                      <option value="30">30 Dias</option>
                      <option value="365">365 Dias</option>
                  </select>
              </div>
          </div>
          <div class="row">
              <div class="d-flex flex-column mb-5 fv-row col-12">
                  <div class="d-flex flex-stack fs-6 fw-bold form-label mb-2">
                      <span>⏳ Quantidade</span>
                  </div>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount" value="1" />
              </div>
          </div>
          <div id="attr_area">
              <div class="row">
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="AttackCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="DefendCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="AgilityCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="LuckCompose" value="0" />
                  </div>
              </div>
          </div>
          <div class="d-flex flex-stack mb-7">
              <div class="me-5">
                  <label class="fs-6 fw-bold form-label">🟢 Ilimitado</label>
                  <div class="fs-7 fw-bold text-muted">
                      Se desmarcado o item <span class="text-success">poderá ser enviado</span>.
                  </div>
              </div>
              <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind" value="1"
                      checked="checked" />
                  <span class="form-check-label fw-bold text-muted"></span>
              </label>
          </div>
          <div class="d-flex flex-stack mb-7">
              <div class="me-5">
                  <label class="fs-6 fw-bold form-label">🤚 Selecionavel</label>
                  <div class="fs-7 fw-bold text-muted">
                      Se marcado o item <span class="text-success">terá que ser escolhido</span> dentre outros como
                      recompensa.
                  </div>
              </div>
              <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input h-20px w-30px" type="checkbox" name="IsSelect" value="1"/>
                  <span class="form-check-label fw-bold text-muted"></span>
              </label>
          </div>
          <div class="text-center">
              <button type="button" onclick="reward.create()" id="btn_reward_create"
                  class="btn btn-sm btn-light-primary w-100">
                  <span class="indicator-label">Adicionar</span>
                  <span class="indicator-progress">
                      adicionando...
                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                  </span>
              </button>
          </div>
      </div>
  </div>
</form>

<form id="version_server" data-require-version="5500"  style="display:none;">
  <div class="row">
      <label class="form-label">📦 Item</label>
      <select class="form-select form-select-sm form-select-solid" data-dropdown-parent="#md_reward_create"
          data-placeholder="Selecione um item" data-allow-clear="true" name="RewardItemID">
      </select>
  </div>
  <div id="info_area" style="display: none">
      <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
          <div class="row mt-7">
              <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                  <label class="form-label">
                      🥾 Level
                  </label>
                  <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                      data-hide-search="true" data-placeholder="Nível" name="StrengthenLevel">
                      <option></option>
                      <option value="0">Sem level</option>
                      @for ($i = 1; $i <= 12; $i++)
                          <option value="{{ $i }}">Nível {{ $i }}</option>
                      @endfor
                      <option value="13">Avanço 1</option>
                      <option value="14">Avanço 2</option>
                      <option value="15">Avanço 3</option>
                  </select>
              </div>
              <div class="d-flex flex-column mb-5 fv-row col-12" id="valid_area">
                  <label class="form-label">🗓️ Validade</label>
                  <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                      data-hide-search="true" name="RewardItemValid">
                      <option value="0" selected>Permanente</option>
                      <option value="1">1 Dia</option>
                      <option value="3">3 Dias</option>
                      <option value="7">7 Dias</option>
                      <option value="15">15 Dias</option>
                      <option value="30">30 Dias</option>
                      <option value="365">365 Dias</option>
                  </select>
              </div>
          </div>
          <div class="row">
              <div class="d-flex flex-column mb-5 fv-row col-12">
                  <div class="d-flex flex-stack fs-6 fw-bold form-label mb-2">
                      <span>⏳ Quantidade</span>
                      <div class="d-flex">
                          <span class="text-muted me-2">mutipla qnt.</span>
                          <label class="form-check form-switch form-check-custom form-check-solid">
                              <input class="form-check-input h-10px w-20px" type="checkbox" name="IsMultipleCount"
                                  value="1">
                              <span class="form-check-label fw-bold text-muted"></span>
                          </label>
                      </div>
                  </div>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount1" value="1" />
              </div>
          </div>
          <div class="row" id="multiple_count" style="display:none;">
              <div class="d-flex flex-column mb-5 fv-row col-3">
                  <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                      <span>Qnt. 2</span>
                  </label>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount2" value="1" />
              </div>
              <div class="d-flex flex-column mb-5 fv-row col-3">
                  <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                      <span>Qnt. 3</span>
                  </label>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount3" value="1" />
              </div>
              <div class="d-flex flex-column mb-5 fv-row col-3">
                  <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                      <span>Qnt. 4</span>
                  </label>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount4" value="1" />
              </div>
              <div class="d-flex flex-column mb-5 fv-row col-3">
                  <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                      <span>Qnt. 5</span>
                  </label>
                  <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                      name="RewardItemCount5" value="1" />
              </div>
          </div>
          <div id="attr_area">
              <div class="row">
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="AttackCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="DefendCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="AgilityCompose" value="0" />
                  </div>
                  <div class="d-flex flex-column mb-5 fv-row col-3">
                      <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                      <input type="text" class="form-control form-control-sm form-control-solid"
                          placeholder="Ex: 0" name="LuckCompose" value="0" />
                  </div>
              </div>
          </div>
          <div class="d-flex flex-stack mb-7">
              <div class="me-5">
                  <label class="fs-6 fw-bold form-label">🟢 Ilimitado</label>
                  <div class="fs-7 fw-bold text-muted">
                      Se desmarcado o item <span class="text-success">poderá ser enviado</span>.
                  </div>
              </div>
              <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind" value="1"
                      checked="checked" />
                  <span class="form-check-label fw-bold text-muted"></span>
              </label>
          </div>
          <div class="d-flex flex-stack mb-7">
              <div class="me-5">
                  <label class="fs-6 fw-bold form-label">🤚 Selecionavel</label>
                  <div class="fs-7 fw-bold text-muted">
                      Se marcado o item <span class="text-success">terá que ser escolhido</span> dentre outros como
                      recompensa.
                  </div>
              </div>
              <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input h-20px w-30px" type="checkbox" name="IsSelect" value="1"/>
                  <span class="form-check-label fw-bold text-muted"></span>
              </label>
          </div>
          <div class="text-center">
              <button type="button" onclick="reward.create()" id="btn_reward_create"
                  class="btn btn-sm btn-light-primary w-100">
                  <span class="indicator-label">Adicionar</span>
                  <span class="indicator-progress">
                      adicionando...
                      <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                  </span>
              </button>
          </div>
      </div>
  </div>
</form>

<form id="version_server" data-require-version="11000"  style="display:none;">
    <div class="row">
        <label class="form-label">📦 Item</label>
        <select class="form-select form-select-sm form-select-solid" data-dropdown-parent="#md_reward_create"
            data-placeholder="Selecione um item" data-allow-clear="true" name="RewardItemID">
        </select>
    </div>
    <div id="info_area" style="display: none">
        <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
            <div class="row mt-7">
                <div class="d-flex flex-column mb-5 fv-row col-6" id="strengthen_area">
                    <label class="form-label">
                        🥾 Level
                    </label>
                    <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                        data-hide-search="true" data-placeholder="Nível" name="StrengthenLevel">
                        <option></option>
                        <option value="0">Sem level</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">Nível {{ $i }}</option>
                        @endfor
                        <option value="13">Avanço 1</option>
                        <option value="14">Avanço 2</option>
                        <option value="15">Avanço 3</option>
                    </select>
                </div>
                <div class="d-flex flex-column mb-5 fv-row col-12" id="valid_area">
                    <label class="form-label">🗓️ Validade</label>
                    <select class="form-select form-select-sm form-select-solid mb-2" data-control="select2"
                        data-hide-search="true" name="RewardItemValid">
                        <option value="0" selected>Permanente</option>
                        <option value="1">1 Dia</option>
                        <option value="3">3 Dias</option>
                        <option value="7">7 Dias</option>
                        <option value="15">15 Dias</option>
                        <option value="30">30 Dias</option>
                        <option value="365">365 Dias</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="d-flex flex-column mb-5 fv-row col-12">
                    <div class="d-flex flex-stack fs-6 fw-bold form-label mb-2">
                        <span>⏳ Quantidade</span>
                        <div class="d-flex">
                            <span class="text-muted me-2">mutipla qnt.</span>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input h-10px w-20px" type="checkbox" name="IsMultipleCount"
                                    value="1">
                                <span class="form-check-label fw-bold text-muted"></span>
                            </label>
                        </div>
                    </div>
                    <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                        name="RewardItemCount1" value="1" />
                </div>
            </div>
            <div class="row" id="multiple_count" style="display:none;">
                <div class="d-flex flex-column mb-5 fv-row col-3">
                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span>Qnt. 2</span>
                    </label>
                    <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                        name="RewardItemCount2" value="1" />
                </div>
                <div class="d-flex flex-column mb-5 fv-row col-3">
                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span>Qnt. 3</span>
                    </label>
                    <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                        name="RewardItemCount3" value="1" />
                </div>
                <div class="d-flex flex-column mb-5 fv-row col-3">
                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span>Qnt. 4</span>
                    </label>
                    <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                        name="RewardItemCount4" value="1" />
                </div>
                <div class="d-flex flex-column mb-5 fv-row col-3">
                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span>Qnt. 5</span>
                    </label>
                    <input type="number" class="form-control form-control-sm form-control-solid" min="1"
                        name="RewardItemCount5" value="1" />
                </div>
            </div>
            <div id="attr_area">
                <div class="row">
                    <div class="d-flex flex-column mb-5 fv-row col-3">
                        <label class="fs-6 fw-bold form-label mb-2">Ataque</label>

                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="AttackCompose" value="0" />
                    </div>
                    <div class="d-flex flex-column mb-5 fv-row col-3">
                        <label class="fs-6 fw-bold form-label mb-2">Defesa</label>

                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="DefendCompose" value="0" />
                    </div>
                    <div class="d-flex flex-column mb-5 fv-row col-3">
                        <label class="fs-6 fw-bold form-label mb-2">Agilidade</label>

                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="AgilityCompose" value="0" />
                    </div>
                    <div class="d-flex flex-column mb-5 fv-row col-3">
                        <label class="fs-6 fw-bold form-label mb-2">Sorte</label>

                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="LuckCompose" value="0" />
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex flex-column mb-5 fv-row col-6">
                        <label class="fs-6 fw-bold form-label mb-2">Ataque Magíco</label>
                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="MagicAttack" value="0" />
                    </div>
                    <div class="d-flex flex-column mb-5 fv-row col-6">
                        <label class="fs-6 fw-bold form-label mb-2">Defesa Mágica</label>

                        <input type="text" class="form-control form-control-sm form-control-solid"
                            placeholder="Ex: 0" name="MagicDefence" value="0" />
                    </div>
                </div>
            </div>
            <div class="d-flex flex-stack mb-7">
                <div class="me-5">
                    <label class="fs-6 fw-bold form-label">🟢 Ilimitado</label>
                    <div class="fs-7 fw-bold text-muted">
                        Se desmarcado o item <span class="text-success">poderá ser enviado</span>.
                    </div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsBind" value="1"
                        checked="checked" />
                    <span class="form-check-label fw-bold text-muted"></span>
                </label>
            </div>
            <div class="d-flex flex-stack mb-7">
                <div class="me-5">
                    <label class="fs-6 fw-bold form-label">🤚 Selecionavel</label>
                    <div class="fs-7 fw-bold text-muted">
                        Se marcado o item <span class="text-success">terá que ser escolhido</span> dentre outros como
                        recompensa.
                    </div>
                </div>
                <label class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input h-20px w-30px" type="checkbox" name="IsSelect" value="1"/>
                    <span class="form-check-label fw-bold text-muted"></span>
                </label>
            </div>
            <div class="text-center">
                <button type="button" onclick="reward.create()" id="btn_reward_create"
                    class="btn btn-sm btn-light-primary w-100">
                    <span class="indicator-label">Adicionar</span>
                    <span class="indicator-progress">
                        adicionando...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</form>
