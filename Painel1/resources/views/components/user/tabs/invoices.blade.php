<div class="tab-pane" id="tab_user_invoice">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <span style="color: #a1a5b7;font-size:13.975px;">
                  Faturas do jogador
                </span>
            </div>
        </div>
        <div class="card-body pt-1 pb-1">
            <div id="no_results">
                @include('components.default.notfound', [
                    'title' => 'Sem faturas',
                    'message' => 'não tem nada por aqui',
                ])
            </div>
            <div class="table-responsive" style="display:none;">
                <table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_users">
                    <tbody class="text-gray-600 fw-bold" id="invoice_list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
