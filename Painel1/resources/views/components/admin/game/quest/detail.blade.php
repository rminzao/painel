<form id="version_server" data-require-version="4100">
    <input type="hidden" name="ID">
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">🏷️ Título</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="Title" value="" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="QuestID" class="form-label required">💾 Tipo De Missão</label>
            <select class="form-select form-select-sm form-select-solid" data-control="select2"
                data-placeholder="Selecione o Tipo" data-hide-search="true" name="QuestID">
                <option></option>
                @foreach ($questTypes as $types => $key)
                    <option value="{{ $types }}" {{ !$loop->first ?: 'selected' }}>
                        {{ $key }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Descrição</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Detail" data-kt-autosize="true"></textarea>
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Objetivo</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Objective" data-kt-autosize="true"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de inicio</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data"
                name="StartDate" value="{{ date('d/m/Y H:i:s') }}" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de término</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data" name="EndDate"
                value="{{ date('2050-m-d H:i:s') }}" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Min.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMinLevel" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Max.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMaxLevel" value="70" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">⬅️ Quest ante.</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="PreQuestID"
                value="0," />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">➡️ Prox.quest</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="NextQuestID"
                value="0," />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-3">
            <label class="fs-6 fw-bold form-label mb-2">🪙 Gold</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGold"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-3">
            <label class="fs-6 fw-bold form-label mb-2">💴 L. cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGiftToken"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-3">
            <label class="fs-6 fw-bold form-label mb-2">💸 Cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardMoney"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-3">
            <label class="fs-6 fw-bold form-label mb-2">🏅 Medalha</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardMedal"
                value="0" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">⭐ Experiência</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGP"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🍯 Mérito</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardOffer"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🔥 Contribuição</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardRiches"
                value="0" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">💱 Pode repetir</label>
            <div class="fs-7 fw-bold text-muted">
                Se desmarcado a missão <span class="text-danger">não poderá</span> ser repetida
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="CanRepeat" value="1"
                checked="checked" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. por dia</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatInterval"
                min="1" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. max</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatMax"
                min="1" value="1" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">📹 Time mode</label>
            <div class="fs-7 fw-bold text-muted">
                Verifica no emulador (padrão: <span class="text-danger">desativado</span>)
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="TimeMode" value="1" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="text-center d-flex mt-7 w-100">
        <button type="button" onclick="quest.update()" class="btn btn-sm btn-light-primary w-100"
            id="btn_quest_update">
            <span class="indicator-label">aplicar alterações</span>
            <span class="indicator-progress">
                aplicando...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>

<form id="version_server" data-require-version="5500" style="display:none;">
    <input type="hidden" name="ID">
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">🏷️ Título</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="Title"
                value="" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="QuestID" class="form-label required">💾 Tipo De Missão</label>
            <select class="form-select form-select-sm form-select-solid" data-control="select2"
                data-placeholder="Selecione o Tipo" data-hide-search="true" name="QuestID">
                <option></option>
                @foreach ($questTypes as $types => $key)
                    <option value="{{ $types }}" {{ !$loop->first ?: 'selected' }}>
                        {{ $key }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Descrição</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Detail" data-kt-autosize="true"></textarea>
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Objetivo</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Objective"
                data-kt-autosize="true"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de inicio</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data"
                name="StartDate" value="{{ date('d/m/Y H:i:s') }}" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de término</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data"
                name="EndDate" value="{{ date('2050-m-d H:i:s') }}" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Min.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMinLevel" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Max.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMaxLevel" value="70" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">⬅️ Quest ante.</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="PreQuestID"
                value="0," />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">➡️ Prox.quest</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="NextQuestID"
                value="0," />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🪙 Gold</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGold"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">💴 L. cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardBindMoney"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">💸 Cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardMoney"
                value="0" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">⭐ Experiência</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGP"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🍯 Mérito</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardOffer"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🔥 Contribuição</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardRiches"
                value="0" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">💱 Pode repetir</label>
            <div class="fs-7 fw-bold text-muted">
                Se desmarcado a missão <span class="text-danger">não poderá</span> ser repetida
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="CanRepeat" value="1"
                checked="checked" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. por dia</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatInterval"
                min="1" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. max</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatMax"
                min="1" value="1" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">📹 Time mode</label>
            <div class="fs-7 fw-bold text-muted">
                Verifica no emulador (padrão: <span class="text-danger">desativado</span>)
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="TimeMode" value="1" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="text-center d-flex mt-7 w-100">
        <button type="button" onclick="quest.update()" class="btn btn-sm btn-light-primary w-100"
            id="btn_quest_update">
            <span class="indicator-label">aplicar alterações</span>
            <span class="indicator-progress">
                aplicando...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>

<form id="version_server" data-require-version="11000" style="display:none;">
    <input type="hidden" name="ID">
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">🏷️ Título</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="Title"
                value="" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="QuestID" class="form-label required">💾 Tipo De Missão</label>
            <select class="form-select form-select-sm form-select-solid" data-control="select2"
                data-placeholder="Selecione o Tipo" data-hide-search="true" name="QuestID">
                <option></option>
                @foreach ($questTypes as $types => $key)
                    <option value="{{ $types }}" {{ !$loop->first ?: 'selected' }}>
                        {{ $key }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Descrição</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Detail" data-kt-autosize="true"></textarea>
        </div>
        <div class="fv-row mb-5 col-6">
            <label for="" class="form-label">📜 Objetivo</label>
            <textarea class="form-control form-control form-control-solid overflow-hiden" name="Objective"
                data-kt-autosize="true"></textarea>
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de inicio</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data"
                name="StartDate" value="{{ date('d/m/Y H:i:s') }}" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">📅 Data de término</label>
            <input class="form-control form-control-sm form-control-solid" placeholder="Selecione a data"
                name="EndDate" value="{{ date('2050-m-d H:i:s') }}" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Min.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMinLevel" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="form-label">🧽 Level Max.</label>
            <input class="form-control form-control-sm form-control-solid" type="number"
                name="NeedMaxLevel" value="70" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">⬅️ Quest ante.</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="PreQuestID"
                value="0," />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">➡️ Prox.quest</label>
            <input type="text" class="form-control form-control-sm form-control-solid" name="NextQuestID"
                value="0," />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🪙 Gold</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGold"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">💴 L. cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardBindMoney"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">💸 Cupons</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardMoney"
                value="0" />
        </div>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">⭐ Experiência</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardGP"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🍯 Mérito</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardOffer"
                value="0" />
        </div>
        <div class="fv-row mb-5 col-4">
            <label class="fs-6 fw-bold form-label mb-2">🔥 Contribuição</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RewardRiches"
                value="0" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">💱 Pode repetir</label>
            <div class="fs-7 fw-bold text-muted">
                Se desmarcado a missão <span class="text-danger">não poderá</span> ser repetida
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="CanRepeat" value="1"
                checked="checked" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="row">
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. por dia</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatInterval"
                min="1" value="1" />
        </div>
        <div class="fv-row mb-5 col-6">
            <label class="fs-6 fw-bold form-label mb-2">💱 Rep. max</label>
            <input type="number" class="form-control form-control-sm form-control-solid" name="RepeatMax"
                min="1" value="1" />
        </div>
    </div>
    <div class="d-flex flex-stack mb-5">
        <div class="me-5">
            <label class="fs-6 fw-bold form-label">📹 Time mode</label>
            <div class="fs-7 fw-bold text-muted">
                Verifica no emulador (padrão: <span class="text-danger">desativado</span>)
            </div>
        </div>
        <label class="form-check form-switch form-check-custom form-check-solid">
            <input class="form-check-input h-20px w-30px" type="checkbox" name="TimeMode" value="1" />
            <span class="form-check-label fw-bold text-muted"></span>
        </label>
    </div>
    <div class="text-center d-flex mt-7 w-100">
        <button type="button" onclick="quest.update()" class="btn btn-sm btn-light-primary w-100"
            id="btn_quest_update">
            <span class="indicator-label">aplicar alterações</span>
            <span class="indicator-progress">
                aplicando...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
        </button>
    </div>
</form>
