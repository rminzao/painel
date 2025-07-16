<?php

namespace App\Http\Controllers\Api\Admin\Game;

use App\Http\Controllers\Api\Api;
use App\Models\Server;
use App\Models\Shop as ModelsShop;
use App\Models\ShopGoods;
use App\Models\ShopGoodsShowList;
use Core\Routing\Request;
use Core\Utils\GameTypes\eReloadType;
use Core\Utils\Wsdl;
use Core\View\Paginator;
use GuzzleHttp\Client;

class Shop extends Api
{
    public function list(Request $request)
    {
        $post = $request->get();
        $sid = $post['sid'] ?? null;
        $search = $post['search'] ?? null;
        $type = $post['type'] ?? 0;
        $page = $post['page'] ?? 1;
        $limit = $post['limit'] ?? 10;
        $onclick = $post['onclick'] ?? 'shop.list';

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsShop())->setTable($server->dbData . '.dbo.Shop');
        $query = $model->select('*');

        //filters
        if ($type != 0) {
            $query = $query->whereIn('ID', function ($query) use ($server, $type) {
                $query
                  ->select('ShopId')
                  ->from("{$server->dbData}.dbo." .
                    ($server->version >= 10000 ? 'Shop_Goods_Show_List' : 'ShopGoodsShowList'))
                  ->whereRaw("Type = $type");
            });
        }

        if ($search != null) {
            $query = filter_var($search, FILTER_VALIDATE_INT) ?
            $query->whereIn('TemplateID', function ($query) use ($server, $search) {
                $query->select('TemplateID')
                    ->from("{$server->dbData}.dbo.Shop_Goods")
                    ->where("TemplateID", $search);
            }) :
            $query->whereIn('TemplateID', function ($query) use ($server, $search) {
                $query->select('TemplateID')
                        ->from("{$server->dbData}.dbo.Shop_Goods")
                        ->where('Name', 'like', "%{$search}%");
            });
        }

        $query = $query->orderBy('ID', 'ASC');

        $pager = new Paginator(url($request->getUri()), onclick: $onclick);
        $pager->pager($query->count(), $limit, $page, 2);

        //get item list
        $shopList = $query->limit($pager->limit())->offset($pager->offset())->get()?->toArray();
        $shopList = array_map(function ($shop) use ($server) {
            $shop['Item'] = $this->getItem($server, intval($shop['TemplateID']));
            $shop['Item']['Icon'] = image_item(intval($shop['TemplateID']), $server->dbData);

            //get shop goodShowList
            $shop['ShopGoodsShowList'] = (new ShopGoodsShowList($server->dbData, $server->version))
              ->where('ShopId', $shop['ID'])
              ->get()
              ?->toArray();

            return $shop;
        }, $shopList);

        return [
            'state' => true,
            'data' => $shopList ?? [],
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
        $sid = $post['sid'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsShop())->setTable($server->dbData . '.dbo.Shop');

        $params = array_map(function ($item) {
            return is_numeric($item) ? floatval($item) : $item;
        }, $post);



        $params['ID'] = $model->max('ID') + 1;
        $params['TemplateID'] = $post['itemID'];
        $params['IsContinue'] = isset($post['IsContinue']) ? 1 : 0;
        $params['IsCheap'] = isset($post['IsCheap']) ? 1 : 0;
        $params['IsBind'] = isset($post['IsBind']) ? 1 : 0;
        $params['IsVouch'] = isset($post['IsVouch']) ? 1 : 0;
        $params['StartDate'] = date_fmt_app($post['StartDate']);
        $params['EndDate'] = date_fmt_app($post['EndDate']);
        $params['GroupID'] = 0;
        unset($params['itemID']);
        unset($params['sid']);

        if (!$model->insert($params)) {
            return [
                'state' => false,
                'message' => 'Falha ao criar produto.'
            ];
        }

        return [
            'state' => true,
            'message' => 'Produto criado com sucesso.'
        ];
    }

    public function update(Request $request)
    {
        $post = $request->post(false);
        $sid = $post['sid'] ?? null;
        $id = $post['ID'] ?? null;

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsShop())->setTable($server->dbData . '.dbo.Shop');
        $shop = $model->find($id);
        if (!$shop) {
            return [
                'state' => false,
                'message' => 'Item não encontrado.'
            ];
        }

        //Convert all data to integer if the parameter is different from StartDate and EndDate
        $post = array_map(function ($item) {
            return is_numeric($item) ? intval($item) : $item;
        }, $post);

        $shop->ShopID = $post['ShopID'];
        $shop->GroupID = 0;
        $shop->TemplateID = $post['TemplateID'];
        $shop->BuyType = $post['BuyType'];
        $shop->IsContinue = isset($post['IsContinue']) ? 1 : 0;
        $shop->IsBind = isset($post['IsBind']) ? 1 : 0;
        $shop->IsVouch = isset($post['IsVouch']) ? 1 : 0;
        $shop->Label = $post['Label'];
        $shop->Beat = $post['Beat'];
        $shop->AUnit = $post['AUnit'];
        $shop->APrice1 = $post['APrice1'];
        $shop->AValue1 = $post['AValue1'];
        $shop->APrice2 = $post['APrice2'];
        $shop->AValue2 = $post['AValue2'];
        $shop->APrice3 = $post['APrice3'];
        $shop->AValue3 = $post['AValue3'];
        $shop->BUnit = $post['BUnit'];
        $shop->BPrice1 = $post['BPrice1'];
        $shop->BValue1 = $post['BValue1'];
        $shop->BPrice2 = $post['BPrice2'];
        $shop->BValue2 = $post['BValue2'];
        $shop->BPrice3 = $post['BPrice3'];
        $shop->BValue3 = $post['BValue3'];
        $shop->CUnit = $post['CUnit'];
        $shop->CPrice1 = $post['CPrice1'];
        $shop->CValue1 = $post['CValue1'];
        $shop->CPrice2 = $post['CPrice2'];
        $shop->CValue2 = $post['CValue2'];
        $shop->CPrice3 = $post['CPrice3'];
        $shop->CValue3 = $post['CValue3'];
        $shop->Sort = $post['Sort'];
        $shop->IsCheap = isset($post['IsCheap']) ? 1 : 0;
        $shop->LimitCount = $post['LimitCount'];
        $shop->StartDate = date_fmt_app($post['StartDate']);
        $shop->EndDate = date_fmt_app($post['EndDate']);

        if (!$shop->save()) {
            return [
                'state' => false,
                'message' => 'Falha ao atualizar item.'
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

        $server = Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $model = (new ModelsShop())->setTable($server->dbData . '.dbo.Shop');
        $shop = $model->find($id);
        if (!$shop) {
            return [
                'state' => false,
                'message' => 'Item não encontrado.'
            ];
        }

        if (!$shop->delete()) {
            return [
                'state' => false,
                'message' => 'Falha ao excluir item.'
            ];
        }

        $modelShowList = (new ShopGoodsShowList())->setTable($server->dbData . '.dbo.ShopGoodsShowList');
        $modelShowList->where('ShopId', $id)->delete();

        return [
            'state' => true,
            'message' => 'Item excluído com sucesso.'
        ];
    }

    public function updateOnGame(Request $request)
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
            $res = $client->request('GET', $server->quest . '/Build/shop/ShopItemList.ashx');
            $client->request('GET', $server->quest . '/Build/shop/ShopGoodsShowList.ashx');
        } catch (\Throwable $th) {
            return [
                'state' => false,
                'message' => 'Servidor inacessível.'
            ];
        }

        if (!strpos(strtolower($res->getBody()), 'succeeded')) {
            return [
                'state' => false,
                'message' => 'Erro ao atualizar shop, verifique se o servidor de destino esta configurado corretamente.'
            ];
        }

        //send wsdl reload
        (new Wsdl())->reload(Wsdl::SHOP, $server);

        return [
            'state' => true,
            'message' => 'Shop atualizado com sucesso.'
        ];
    }

    protected function getItem(Server $server, int $templateId)
    {
        $model = (new ShopGoods())->setTable($server->dbData . '.dbo.Shop_Goods');
        $item = $model->select('Name', 'NeedSex')->where('TemplateID', $templateId)->first()?->toArray();
        return $item;
    }
}
