<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Server;
use App\Models\ShopGoods;
use App\Models\UserGoods;
use App\Models\UserMessages;
use Carbon\Carbon;
use Core\Routing\Request;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Item extends Api
{
    /**
     * It returns a list of items from the database
     * @param Request request The request object.
     * @return The return is an array with the following keys:
     */
    public function list(Request $request): array
    {
        $post = $request->get();

        //filter and valid page request
        $page = filter_var($post['page'], FILTER_VALIDATE_INT);
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        $category = $post['category'] ?? 0;
        $search = $post['search'] ?? '';
        $onclick = $post['onclick'] ?? 'getItemList';
        $limit = $post['limit'] ?? 36;

        if (!$page or !$sid) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        //find server data
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //get servers
        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('TemplateID', $search) :
                $query->where('Name', 'LIKE', "%{$search}%");
        }


        if ($category != 0) {
            if (filter_var($category, FILTER_VALIDATE_INT)) {
                $query = $query->where('CategoryID', $category);
            }

            if ($category == 'payBox') {
                $query = $query->where([
                    ['Property1', 6],
                    ['Property2', 11],
                ]);
            }

            if ($category == 'exp_package') {
                //(Property1 = '6') AND (Property2 = '9') AND (Property3 = '0')
                $query = $query->where([
                    ['Property1', 6],
                    ['Property2', 9],
                    ['Property3', 0]
                ]);
            }

            if ($category == 'advanceStone') {
                $query = $query->where([
                    ['CategoryID', 11],
                    ['Property1', 45]
                ]);
            }

            if ($category == 'illustrationMount') {
                $query = $query->where([
                    ['CategoryID', 11],
                    ['Property1', 82]
                ]);
            }

            if ($category == 'bottle') {
                $query = $query->where([
                    ['CategoryID', 11],
                    ['Property1', 120]
                ]);
            }

            if ($category == 'gourd') {
                $query = $query->where([
                    ['CategoryID', 11],
                    ['Property1', 22]
                ]);
            }

            //param1.TemplateID >= 1120098 && param1.TemplateID <= 1120101

            if ($category == 'potions') {
                $query = $query->where([
                    ['CategoryID', 11],
                    ['Property1', '>=', 74],
                    ['Property1', '<=', 80],
                ]);
            }

            if ($category == 'goldBless') {
                $query = $query->whereIn('TemplateID', [11560, 11561, 11562]);
            }

            if ($category == 'compose') {
                $query = $query->where([
                    ['Property1', 1],
                    ['CategoryID', 11]
                ]);
            }

            if ($category == 'formuleFusion') {
                $query = $query->where([
                    ['Property1', 8],
                    ['CategoryID', 11]
                ]);
            }

            if ($category == 'timeBox') {
                $query = $query->where([
                    ['Property1', 6],
                    ['Property2', 4],
                    ['CategoryID', 11]
                ]);
            }

            if ($category == 'shellWeapon') {
                $query = $query->where('CategoryID', 64);
            }

            if ($category == 'box') {
                $query = $query->where(function ($query) {
                    $query->where([
                        ['Property1', 6],
                        ['CategoryID', 11]
                    ])->orWhere([
                        ['Property1', 114],
                        ['CategoryID', 11]
                    ]);
                });
            }

            if ($category == 'bead') {
                $query = $query->where('CategoryID', 11)->where('Property1', 31);
            }

            if ($category == 'symbol') {
                $query = $query->where('CategoryID', 11)->where('Property1', 3);
            }

            if ($category == 'fusion') {
                $query = $query->where([
                    ['FusionType', '!=', 0],
                    ['FusionRate', '>', 0]
                ]);
            }

            if ($category == 'strengthStone') {
                $query = $query->where(function ($query) {
                    $query->where([
                        ['CategoryID', 11],
                        ['Property1', 2]
                    ])->orWhere([
                        ['CategoryID', 11],
                        ['Property1', 35]
                    ]);
                });
            }

            if ($category == 'exp_bottle') {
                $query = $query->where(function ($query) {
                    $query->where([
                        ['CategoryID', 11],
                        ['Property1', 21]
                    ]);
                });
            }
        }



        $query = $query->orderBy('TemplateID', 'ASC');

        //start paginator instance
        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 1);

        //get item list
        $items = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();

        foreach ($items as $_item) {
            $_item['Equipment'] = image_item($_item['TemplateID'], $server->dbData, true);
            $_item['Icon'] = image_item($_item['TemplateID'], $server->dbData, res: $server->resource);
            if ($_item['Name'] == '') {
                $_item['Name'] = 'Sem nome';
            }
            $_items[] = $_item;
        }

        return [
            'state' => true,
            'items' => $_items ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }

    public function create(Request $request)
    {
    }

    /**
     * It updates an item in the database
     *
     * @param Request request The request object.
     *
     * @return array An array with the state of the operation and a message.
     */
    public function update(Request $request): array
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;

        //find server data
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        //get item data
        $item = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods')->find($post['TemplateID']);
        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item não encontrado.'
            ];
        }

        //update item data
        unset($post['sid']);
        if (!$item->update($post)) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item atualizado com sucesso.'
        ];
    }

    public function delete(Request $request)
    {
        $post = $request->get();

        $sid = $post['sid'] ?? null;
        $id = $post['id'] ?? null;

        //find server data
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        //get item data
        $item = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods')->find($id);
        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item não encontrado.'
            ];
        }

        //delete item
        if (!$item->delete()) {
            return [
                'state' => false,
                'message' => 'Erro ao deletar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'O item <span class="text-primary">' . $item->Name . '</span> deletado com sucesso.'
        ];
    }

    public function duplicate(Request $request)
    {
        $post = $request->post(false);

        $sid = $post['sid'] ?? 0;
        $id = $post['id'] ?? 0;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');

        $item = $model->where('TemplateID', $id)?->first()?->replicate()?->toArray();

        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item não encontrado.'
            ];
        }

        $item['TemplateID'] =  $model->max('TemplateID') + 1;
        $item['Name'] =  $item['Name'] . ' #duplicado';

        if (!$model->insert($item)) {
            return [
                'state' => false,
                'message' => 'Erro ao duplicar item.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Item duplicado com sucesso.'
        ];
    }

    /**
     * It returns a list of items that match the search term
     * @param Request request The request object.
     * @return An array with two keys:
     */
    public function find(Request $request): array
    {
        $post = $request->get();
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $search = $post['search']['term'] ?? '';

        if (!$sid) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado.'
            ];
        }

        if ($search == '') {
            return [
                'state' => true,
                'items' => []
            ];
        }

        //find server data
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        //get servers
        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $query = $model->select('*');

        //filters
        if ($search != '') {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
                $query->where('TemplateID', $search) :
                $query->where('Name', 'LIKE', "%{$search}%");
        }

        $query = $query->orderBy('TemplateID', 'ASC');

        //get item list
        $items = $query->limit(10)->get()->toArray();

        $itemList = array_map(function ($items) use ($server) {
            $items['Icon'] = image_item($items['TemplateID'], $server->dbData);
            return $items;
        }, $items);

        return [
            'state' => true,
            'items' => $itemList
        ];
    }

    /**
     * Get the list of categories from the database
     * @param Request request The request object.
     * @return An array with two keys:
     */
    public function getCategoryList(Request $request): array
    {
        $post = $request->get();

        //filter sid input
        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);
        if (!$sid) {
            return [
                'state' => false,
                'message' => 'Invalid data.'
            ];
        }

        //find server data
        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods_Categorys');
        $_categories = $model->orderBy('id', 'ASC')->get()?->toArray();

        $customCat = getLanguage('item.category.');

        $data = array_merge($customCat, $_categories);

        usort($data, function ($x, $y) {
            return strcasecmp($x['Name'], $y['Name']);
        });

        return [
            'state' => true,
            'categories' => $data ?? [],
        ];
    }

    /**
     * Get info about an item
     * @param Request request The request object.
     * @return The item information.
     */
    public function getInfo(Request $request): array
    {
        //filter and validate post data
        $post = $request->post(false);
        if (!filter_var_array($post, FILTER_VALIDATE_INT)) {
            return [
                'state' => false,
                'message' => 'Id do servidor ou do item não é válido'
            ];
        }

        //find server
        $server = Server::find($post['sid']);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado'
            ];
        }

        //find item on db
        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $item = $model->where('TemplateID', $post['id'])->first();
        if (!$item) {
            return [
                'state' => false,
                'message' => 'Item não encontrado'
            ];
        }

        //get item image
        $item->Icon = image_item($item->TemplateID, $server->dbData);

        //return
        return [
            'state' => true,
            'info' => $item
        ];
    }

    /**
     * Send items to characters
     * @param Request request The request object.
     * @return An array with two elements:
     */
	public function send(Request $request): array
	{
		try {

			$post = $request->post(false);
			$chars = [];

			if (!isset($post['attachments']) or empty($post['attachments'])) {
				return [
					'state' => false,
					'message' => 'Nenhum item selecionado'
				];
			}

			$server = Server::find($post['sid']);
			if (!$server) {
				return [
					'state' => false,
					'message' => 'Servidor não encontrado'
				];
			}

			$uids = isset($post['uid']) ? $post['uid'] : [];
			$modelChar = (new Character())->setTable($server->dbUser . '.dbo.Sys_Users_Detail');

			if (isset($post['isOnline'])) {
				$uids = $modelChar->select('UserID')->where('State', 1)->get()->toArray();
			}

			if (empty($uids)) {
				return [
					'state' => false,
					'message' => 'Nenhum personagem selecionado'
				];
			}

			//send item for chars
			foreach ($uids as $uid) {
				//find character
				$char = $modelChar->where('UserId', $uid)->first();
				if (!$char) {
					return [
						'state' => false,
						'message' => 'Usuario nao encontrado'
					];
				}

				//build attachments list
				$attachments = $this->buildAttachments($post['attachments']);

				$sentItemsLog = []; // para armazenar TemplateID e contagem enviados

				foreach ($attachments as $group) {
					$groupIds = [];

					//foreach group attachment
					foreach ($group as $attachment) {
						$attachment = (object) $attachment;

						if (in_array($attachment->TemplateID, [-200, -100])) {
							$groupIds[$attachment->TemplateID] = $attachment->Count;
							// salvar também para log
							$sentItemsLog[] = "TemplateID: {$attachment->TemplateID}, Nome: {$attachment->Name}, Quantidade: {$attachment->Count}";
							continue;
						}
						
						$shopGoodsModel = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
						$item = $shopGoodsModel->where('TemplateID', $attachment->TemplateID)->first();
						$itemName = $item ? $item->Name : 'Nome desconhecido';

						//create user goods
						$goodsModel = (new UserGoods())->setTable($server->dbUser . '.dbo.Sys_Users_Goods');
						$rewardGoods = $goodsModel->create([
							'UserID' => $char->UserID,
							'BagType' => 0,
							'TemplateID' => $attachment->TemplateID,
							'Place' => -1,
							'Count' => $attachment->Count,
							'IsJudge' => 1,
							'Color' => null,
							'IsExist' => 1,
							'StrengthenLevel' => $attachment->StrengthenLevel,
							'AttackCompose' => $attachment->Attack,
							'DefendCompose' => $attachment->Defence,
							'LuckCompose' => $attachment->Luck,
							'AgilityCompose' => $attachment->Agility,
							'IsBinds' => $attachment->IsBinds,
							'BeginDate' => "",
							'ValidDate' => $attachment->Valid
						]);

						if (!$rewardGoods) {
							return false;
						}

						$groupIds[] = $rewardGoods->ItemID;

						// salvar para log
						$sentItemsLog[] = "TemplateID: {$attachment->TemplateID}, Nome: {$itemName}, Quantidade: {$attachment->Count}";
					}

					//create mail
					$annex1 = $groupIds[0] ?? 0;
					$annex2 = $groupIds[1] ?? 0;
					$annex3 = $groupIds[2] ?? 0;
					$annex4 = $groupIds[3] ?? 0;
					$annex5 = $groupIds[4] ?? 0;

					$money = $groupIds[-200] ?? 0;
					$gold = $groupIds[-100] ?? 0;

					$messageModel = (new UserMessages())->setTable($server->dbUser . '.dbo.User_Messages');
					$rewardMessage = $messageModel->create([
						'SenderID' => 0,
						'Sender' => 'Sistema',
						'ReceiverID' => $char->UserID,
						'Receiver' => $char->NickName,
						'Title' => $post['title'] ?? 'Recompensa de recarga',
						'Content' => $post['content'] ?? 'Ola jogador voce recebeu esta(s) recompensa(s) do sistema.',
						'IsRead' => 0,
						'IsDelR' => 0,
						'IfDelS' => 0,
						'IsDelete' => 0,
						'Annex1' => $annex1,
						'Annex2' => $annex2,
						'Annex3' => $annex3,
						'Annex4' => $annex4,
						'Annex5' => $annex5,
						'Gold' => $gold,
						'Money' => $money,
						'IsExist' => 1,
						'Type' => 51,
						'Remark' =>
						"Gold:$gold,Money:$money,Annex1:$annex1,Annex2:$annex2,
						Annex3:$annex3,Annex4:$annex4,Annex5:$annex5,
						GiftToken:0"
					]);

					if (!$rewardMessage) {
						return false;
					}
				}

				// Log do envio para este personagem
				log_system(
					$this->user->id,
					'Enviou itens [' . implode('; ', $sentItemsLog) . "] ao jogador [<b>{$char->NickName}</b>] (UserID: {$char->UserID}) do servidor (ID: {$server->id}).",
					$request->getUri(),
					'admin.rewards.send'
				);

				log_webhook(
					'📢 AVISO! - ITENS FORAM ENVIADOS! 📢' . "\n" .
					'**' . $_ENV["APP_NAME"] . '** - ' . $_ENV["APP_URL"] . "\n" .
					'[`' . $this->user->id . '`] - ' . $this->user->first_name . ' ' . $this->user->last_name .
					' enviou os itens [' . implode('; ', $sentItemsLog) . "] ao jogador **{$char->NickName}** (UserID: `{$char->UserID}`) no servidor de ID `{$server->id}` chamado **{$server->name}**"
				);

				$chars[] = $char->NickName;

				//send wsdl recharge
				if ($server->wsdl != '') {
					$parameters = [
						"playerID" => (int) $char->UserID
					];

					$settings = json_decode(unserialize($server->settings));
					if ($settings->areaid != "" || $settings->areaid != null) {
						$parameters = array_merge($parameters, [
							'zoneId' => $settings->areaid
						]);
					}

					$wsdl = new Wsdl($server->wsdl);
					$wsdl->method = 'MailNotice';
					$wsdl->paramters = $parameters;
					if (!$wsdl->send()) {
					}
				}
			}

			return [
				'state' => true,
				'message' => "Itens enviados com sucesso ao's jogador'es <b>" . implode(', ', $chars) . "</b>
						  do servidor <b>{$server->name}</b>"
			];
		} catch (\Throwable $th) {
			dd($th);
		}
	}

    /**
     * It receives a request from the client, validates the server id, sends a request to the server,
     * and returns a response to the client
     *
     * @param Request request The request object.
     *
     * @return A list of items.
     */
    public function onGameUpdate(Request $request)
    {
        $post = $request->get();

        $sid = filter_var($post['sid'], FILTER_VALIDATE_INT);

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $client = new Client();
        try {
            $res = $client->request('GET', $server->quest . '/build/CreateAllXml.ashx');
            //$client->request('GET', $server->quest . '/LoadBoxTemp.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'succeeded')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar a lista de itens.'
            ];
        }

        //send wsdl reload
        (new Wsdl())->reload(Wsdl::ITEM, $server);

        return [
            'state' => true,
            'message' => 'Lista de items atualizada com sucesso.'
        ];
    }

    /**
     * This function is used to build the attachments array for the API call.
     * @param array _item The array of items to be attached to the email.
     * @return An array of arrays.
     */
    protected function buildAttachments(array $_item): array
    {
        //append arrays to single
        for ($i = 0; $i < count($_item['TemplateID']); $i++) {
            foreach (array_keys($_item) as $keys) {
                $items[$i][$keys] = $_item[$keys][$i];
            }
        }

        return array_chunk($items, 5);
    }
}
