<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\ChargeMoney;
use App\Models\Product as ModelsProduct;
use App\Models\ProductReward;
use App\Models\Server;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;

class Product extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();

        $page = $post['page'] ?? 1;
        $sid = $post['sid'] ?? 0;
        $search = $post['search'] ?? '';
        $onclick = $post['onclick'] ?? 'product.list';
        $limit = $post['limit'] ?? 10;

        $model = new ModelsProduct();
        $query = $model->select('*');

        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('id', $search) :
                $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($sid != 0) {
            $query = $query->where('sid', $sid);
        }

        $query = $query->orderBy('id', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 2);

        $data = $query
            ->limit($pager->limit())
            ->offset($pager->offset())
            ->get()?->toArray() ?? [];

        foreach ($data as &$product) {
            $product['value'] = str_price($product['value']);
            $product['server'] = Server::where('id', $product['sid'])->first()?->toArray();
        }

        return [
            'status' => true,
            'data' => $data,
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create(Request $request)
    {
        $post = $request->post(false);

        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Todos os campos são obrigatórios'
            ];
        }

        $params = $post;
        $params['reward'] = isset($post['reward']) ? 1 : 0;
        $params['active'] = isset($post['active']) ? 1 : 0;
        $params['value'] = str_replace(',', '.', $post['value']);

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'O servidor informado não foi encontrado, atualize a página e tente novamente.'
            ];
        }

        $model = new ModelsProduct();
        if (
            in_array($params['type'], [3, 4]) &&
            $model->where('type', 3)->where('active', 1)->first()
        ) {
            $type = $params['type'] == 3 ? 'Laboratório' : 'Missão';
            return [
                'state' => false,
                'message' => "Ja existe um produto do tipo <span>{$type}</span> ativo atualmente. Desative-o ou exlua para adicionar outro."
            ];
        }

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar produto'
            ];
        }

        log_system(
            $this->user->id,
            'Adicionou uma recarga de nome [<b>' . $params['name'] . '</b>] ao servidor de id (' . $params['sid'] . ').',
            $request->getUri(),
            'admin.product.create'
        );
		
		log_webhook(
			'📢 AVISO! - <@972606046797455391> / <@1000939518041522186> - RECARGA FOI ENVIADA! 📢' . "\n" .
			'**' . $_ENV["APP_NAME"] . '** - ' . $_ENV["APP_URL"] . "\n" .
			'[`' . $this->user->id . '`] - ' . $this->user->first_name . ' ' . $this->user->last_name .
			' enviou **' . $post['ammount'] . '** cupons aos jogadores **' . implode(', ', $usersSended) .
			'** (UserID: `' . $character->UserID . '`) no servidor de ID `' . $server->id . '` chamado **' . $server->name . '**'
		);

        return [
            'state' => true,
            'message' => 'Recarga adicionada com sucesso ao servidor <span class="text-primary">' . $server->name . '</span>.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);

        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => 'Todos os campos são obrigatórios'
            ];
        }

        $params = $post;
        $params['reward'] = isset($post['reward']) ? 1 : 0;
        $params['active'] = isset($post['active']) ? 1 : 0;
        $params['value'] = str_replace(',', '.', $post['value']);

        unset($params['id']);

        $product = ModelsProduct::find($post['id']);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Produto não encontrado'
            ];
        }

        if (
            in_array($params['type'], [3, 4]) &&
            (new ModelsProduct)
				->where([
					['id', '!=', $post['id']],
					['type', 3],
					['active', 1],
				])->first()
        ) {
            $type = $params['type'] == 3 ? 'Laboratório' : 'Missão';
            return [
                'state' => false,
                'message' => "Ja existe um produto do tipo <span>{$type}</span> ativo atualmente. Desative-o ou exlua para adicionar outro."
            ];
        }

        if (!$product->update($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar produto'
            ];
        }

        return [
            'state' => true,
            'message' => 'Produto atualizado com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();
        $id = $post['id'] ?? '';

        $product = ModelsProduct::find($id);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Produto informado não existe.'
            ];
        }

        if (!$product->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao deletar produto'
            ];
        };

        ProductReward::where('pid', $id)->delete();

        return [
            'state' => true,
            'message' => 'Produto deletado com sucesso.'
        ];
    }

    public function duplicate(Request $request)
    {
        $post = $request->post(false);
        $id = $post['id'] ?? '';

        $product = ModelsProduct::find($id);
        if (!$product) {
            return [
                'state' => false,
                'message' => 'Produto informado não existe.'
            ];
        }

        $params = $product->toArray();
        $params['name'] = $params['name'] . ' #duplicado';

        unset($params['id']);

        $model = new ModelsProduct();
        if (!$newId = $model->insertGetId($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao duplicar produto'
            ];
        }

        $rewards = ProductReward::where('pid', $id)->get()?->toArray();
        foreach ($rewards as $reward) {
            unset($reward['id']);
            $reward['pid'] = $newId;
            ProductReward::insert($reward);
        }

        return [
            'state' => true,
            'message' => 'Produto duplicado com sucesso.'
        ];
    }

    public function send(Request $request)
    {
        $post = $request->post(false);

        if ($post['ammount'] == 0) {
            return [
                'state' => false,
                'message' => 'Quantidade de coupons nao pode ser igual a 0.'
            ];
        }

        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor nao encontrado.'
            ];
        }

        //check if uid is empty
        if (sizeof($post['uid'] ?? []) < 1) {
            return [
                'state' => false,
                'message' => 'Nenhum jogador foi selecionado.'
            ];
        }

        //users sended list
        $usersSended = [];

        foreach ($post['uid'] as $uid) {
            $model = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');
            $character = $model->where('UserID', '=', $uid)->first();
            if (!$character) {
                return [
                    'state' => false,
                    'message' => 'Personagem não encontrado.'
                ];
            }

            $hash = str_hash(20);

            $chargeMoney = (new ChargeMoney())->setTable($server->dbUser . '.dbo.Charge_Money');
            $chargeMoney->ChargeID = $hash;
            $chargeMoney->UserName = $character->UserName;
            $chargeMoney->Money = $post['ammount'];
            $chargeMoney->Date = date('Y-m-d H:i:s');
            $chargeMoney->CanUse = 1;
            $chargeMoney->PayWay = 'Administração - ' . ($post['payway'] ?? 'Não especificado');
            $chargeMoney->NeedMoney = '0';
            $chargeMoney->IP = '0.0.0.0';
            $chargeMoney->NickName = $character->NickName;

            if (!$chargeMoney->save()) {
                return [
                    'state' => false,
                    'message' => 'Erro interno com banco de dados.'
                ];
            }

            $usersSended[] = $character->NickName;

            //send wsdl recharge
            if ($server->wsdl != '') {
                $parameters = [
                    "userID" => (int) $character->UserID,
                    "chargeID" => $hash,
                ];

                $settings = json_decode(unserialize($server->settings));
                if ($settings->areaid != "" || $settings->areaid != null) {
                    $parameters = array_merge($parameters, [
                        'zoneId' => $settings->areaid
                    ]);
                }


                $wsdl = new Wsdl($server->wsdl);
                $wsdl->method = 'ChargeMoney';
                $wsdl->paramters = $parameters;
                if (!$wsdl->send()) {
                    // TODO: send telegram critical error
                }
            }
        }

        //log
        log_system(
            $this->user->id,
            'Enviou uma recarga de [<b>' . $post['ammount'] .
                '</b>] coupons ao\'s jogador\'es [<b>' . implode(', ', $usersSended) .
                '</b>](' . $character->UserID . ') do servidor de id
            (' . $server->id . ').',
            $request->getUri(),
            'admin.recharge.send'
        );

		log_webhook(
			'📢 AVISO! - RECARGA FOI ENVIADA! 📢' . "\n" .
			'**' . $_ENV["APP_NAME"] . '** - ' . $_ENV["APP_URL"] . "\n" .
			'[`' . $this->user->id . '`] - ' . $this->user->first_name . ' ' . $this->user->last_name .
			' enviou **' . $post['ammount'] . '** cupons aos jogadores **' . implode(', ', $usersSended) .
			'** (UserID: `' . $character->UserID . '`) no servidor de ID `' . $server->id . '` chamado **' . $server->name . '**'
		);

        return [
            'state' => true,
            'message' => "Cupons enviados com sucesso ao's jogador'es <b>" . implode(', ', $usersSended) . "</b>
          do servidor <b>[{$server->name}]</b>."
        ];
    }
}
