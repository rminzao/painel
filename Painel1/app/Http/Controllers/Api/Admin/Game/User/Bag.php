<?php

namespace App\Http\Controllers\Api\Admin\Game\User;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Server;
use App\Models\ShopGoods;
use Core\Routing\Request;
use Core\View\Paginator;

class Bag extends Api
{
    public function list(Request $request)
    {
        $get = $request->get();

        $page = $get['page'] ?? 1;
        $sid = $get['sid'] ?? null;
        $search = $get['search'] ?? null;
        $limit = $get['limit'] ?? 5;
        $type = $get['type'] ?? 'all';
        $category = $get['category'] ?? 'all';
        $uid = $get['uid'] ?? null;

        if (!$server = Server::find($sid)) {
            return [
                'state' => false,
                'message' => 'Servidor informado, não foi encontrado.'
            ];
        }

        $query = (new Character($server->dbUser))
          ->goods(uid: $uid)
          ->where('Place', '<>', '-1');

        if ($type != 'all') {
            $query = $query->where('BagType', $type);
        }

        if ($category != 'all') {
            $query = $query->whereIn('TemplateID', function ($query) use ($server, $category) {
                $query->select('TemplateID')
                ->from("{$server->dbData}.dbo.Shop_Goods")
                ->where("CategoryID", $category);
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

        $query = $query->orderBy('ItemID', 'DESC');

        $pager = new Paginator(url($request->getUri()), onclick: "bag.list");
        $pager->pager($query->count(), $limit, $page, 2);

        $data = $query
          ->limit($pager->limit())
          ->offset($pager->offset())
          ->get()
          ?->toArray();

        foreach ($data as &$item) {
            if ($goods = (new ShopGoods($server->dbData))->find($item['TemplateID'])) {
                $item = array_merge($item, $goods?->toArray());
                $item['Icon'] = image_item($item['TemplateID'], $server->dbData);
            }
        }

        return [
            'state' => true,
            'data' => $data ?? [],
            'paginator' => [
                'total' => $pager->pages(),
                'current' => $pager->page(),
                'rendered' => $pager->render()
            ]
        ];
    }
}
