<?php

use Core\Routing\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Web\Admin\FuguraController;
use App\Http\Controllers\Web;
use App\Http\Controllers\Web\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$router->get('/', [
  'middleware' => [
    'check-logged-user'
  ],
  fn () => new Response(200, (new Web\Auth())->signin())
]);

$router->get('/storage', [fn () => new Response(200, (new Web\Controller())->storage(), 'image')]);
$router->get('/links', [fn () => new Response(200, (new Web\App\Link())->index())]);
$router->get('/sair', [fn () => new Response(200, (new Web\Controller())->logout())]);

#region Auth
$router->get('/auth/entrar', [
  'middleware' => [
    'check-logged-user'
  ],
  fn () => new Response(200, (new Web\Auth())->signin())
]);
$router->get('/auth/cadastro', [
  'middleware' => [
    'check-logged-user'
  ], fn () => new Response(200, (new Web\Auth())->signup())
]);
$router->get('/auth/recuperar-senha', [
  'middleware' => [
    'check-logged-user'
  ],
  fn () => new Response(200, (new Web\Auth())->forget())
]);
$router->get('/auth/recuperar-senha/{hash}', [
  'middleware' => [
    'check-logged-user'
  ],
  fn () => new Response(200, (new Web\Auth())->forgetConfirm($hash))
]);
#endregion

#region App
$router->get('/app/lobby', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Lobby())->index())
]);
$router->get('/app/notices', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Notices())->index())
]);
$router->get('/app/shop', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Shop())->index())
]);
$router->get('/app/notices/{uri}', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn ($uri) => new Response(200, (new Web\App\Notices())->detail($uri))
]);
$router->get('/app/serverlist', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Server())->list())
]);
$router->get('/app/ranking', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Ranking())->index())
]);
$router->get('/app/jogar/{sid}', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn ($sid) => new Response(200, (new Web\App\Play())->index($sid))
]);

$router->get('/app/recarga', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Recharge())->index())
]);
$router->get('/app/recarga/{id}', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn ($id) => new Response(200, (new Web\App\Recharge())->detail($id))
]);

$router->get('/app/me/account/overview', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Profile())->overview())
]);
$router->get('/app/me/account/characters', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Profile())->characters())
]);
$router->get('/app/me/account/invoices', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Profile())->invoices())
]);
$router->get('/app/me/account/referrals', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Profile())->referrals())
]);
$router->get('/app/me/account/settings', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Profile())->settings())
]);
$router->get('/app/me/ticket/list', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Ticket())->list())
]);
$router->get('/app/me/ticket/new', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn () => new Response(200, (new Web\App\Ticket())->create())
]);
$router->get('/app/me/ticket/detail/{id}', [
  'middleware' => [
    'check-unlogged-user'
  ],
  fn ($id) => new Response(200, (new Web\App\Ticket())->detail($id))
]);
#endregion

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

$router->get('/admin/jogar/{uid}/{sid}', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($uid, $sid) => new Response(200, (new Web\Admin\Play())->index($uid, $sid))
]);

$router->get('/admin/server/list', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Server())->index())
]);

$router->get('/admin/ecommerce/product', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Product())->index())
]);
$router->get('/admin/ecommerce/product/code', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\ProductCode())->index())
]);
$router->get('/admin/ecommerce/dashboard', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Invoice())->dashboard())
]);
$router->get('/admin/ecommerce/invoice/list', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Invoice())->list())
]);
$router->get('/admin/ecommerce/invoice/create', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Invoice())->create())
]);

#region Fugura System
$router->get('/admin/gameutils/fugura', [
    'middleware' => ['require-admin-view'],
    fn () => new Response(200, (new FuguraController())->index())
]);

$router->get('/admin/gameutils/fugura/show/{id}', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new FuguraController())->show($id)
]);

$router->post('/admin/gameutils/fugura/store', [
    'middleware' => ['require-admin-view'],
    fn () => (new FuguraController())->store()
]);

$router->post('/admin/gameutils/fugura/update/{id}', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new FuguraController())->update($id)
]);

$router->post('/admin/gameutils/fugura/delete/{id}', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new FuguraController())->destroy($id)
]);

$router->get('/admin/gameutils/fugura/{id}/items', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new FuguraController())->getItems($id)
]);

$router->post('/admin/gameutils/fugura/{id}/items', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new FuguraController())->addItem($id)
]);

$router->post('/admin/gameutils/fugura/{id}/items/{itemId}/update', [
    'middleware' => ['require-admin-view'],
    fn ($id, $itemId) => (new FuguraController())->updateItem($id, $itemId)
]);

$router->post('/admin/gameutils/fugura/{id}/items/{itemId}/delete', [
    'middleware' => ['require-admin-view'],
    fn ($id, $itemId) => (new FuguraController())->deleteItem($id, $itemId)
]);

$router->get('/admin/gameutils/items/search', [
    'middleware' => ['require-admin-view'],
    fn () => (new FuguraController())->searchItems()
]);

$router->get('/admin/gameutils/items/info', [
    'middleware' => ['require-admin-view'],
    fn () => (new FuguraController())->getShopItemInfo()
]);
#endregion

$router->get('/admin/game/users', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Game\User())->list())
]);

$router->get('/admin/users', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\User())->list())
]);

$router->get('/admin/users/equip', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Equip())->getList())
]);

$router->get('/admin/users/{id}', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\User())->detail($id))
], 'admin.users.detail');

$router->get('/admin/game/drop', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Drop())->index())
]);
$router->get('/admin/game/item', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Item())->index())
]);
$router->get('/admin/game/quest', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Quest())->index())
]);

#region NPC System
$router->get('/admin/game/npc', [
    'middleware' => ['require-admin-view'],
    fn () => new Response(200, (new Admin\Game\Npc())->index())
]);

$router->get('/admin/game/npc/show/{id}', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new Admin\Game\Npc())->show($id)
]);

$router->post('/admin/game/npc/store', [
    'middleware' => ['require-admin-view'],
    fn () => (new Admin\Game\Npc())->store()
]);

$router->post('/admin/game/npc/{id}/details', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new Admin\Game\Npc())->updateDetails($id)
]);

$router->post('/admin/game/npc/{id}/attributes', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new Admin\Game\Npc())->updateAttributes($id)
]);

$router->post('/admin/game/npc/{id}/delete', [
    'middleware' => ['require-admin-view'],
    fn ($id) => (new Admin\Game\Npc())->destroy($id)
]);

$router->get('/admin/game/npc/calculate', [
    'middleware' => ['require-admin-view'],
    fn () => (new Admin\Game\Npc())->calculateAttributes()
]);

$router->post('/admin/game/npc', [
    'middleware' => ['require-admin-view'],
    fn () => (new Admin\Game\Npc())->store()
]);
#endregion

// ROTAS MISSIONS - by rm

$router->get('/admin/game/event/missions', [
    'middleware' => ['require-admin-view'],
    fn () => new Response(200, (new \App\Http\Controllers\Web\Admin\Events\Mission())->index())
]);

//  API para dados de missões
$router->get('/admin/game/event/missions/data', [
    'middleware' => ['require-admin-view'],
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->getData()
]);

$router->get('/admin/game/event/missions/show/{activityType}/{subActivityType}', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->show($activityType, $subActivityType)
]);

$router->post('/admin/game/event/missions/store', [
    'middleware' => ['require-admin-view'],
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->store()
]);

$router->post('/admin/game/event/missions/{activityType}/{subActivityType}/update', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->update($activityType, $subActivityType)
]);

$router->delete('/admin/game/event/missions/{activityType}/{subActivityType}/delete', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->destroy($activityType, $subActivityType)
]);

$router->get('/admin/game/event/missions/{activityType}/{subActivityType}/items', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->getItems($activityType, $subActivityType)
]);

$router->post('/admin/game/event/missions/{activityType}/{subActivityType}/items', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->addItem($activityType, $subActivityType)
]);

$router->post('/admin/game/event/missions/{activityType}/{subActivityType}/items/{templateId}/update', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType, $templateId) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->updateItem($activityType, $subActivityType, $templateId)
]);

$router->post('/admin/game/event/missions/{activityType}/{subActivityType}/items/{templateId}/delete', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType, $templateId) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->deleteItem($activityType, $subActivityType, $templateId)
]);

$router->delete('/admin/game/event/missions/{activityType}/{subActivityType}/items/all', [
    'middleware' => ['require-admin-view'],
    fn ($activityType, $subActivityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->deleteAllItems($activityType, $subActivityType)
]);

$router->get('/admin/game/event/missions/items/search', [
    'middleware' => ['require-admin-view'],
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->searchItems()
]);

$router->get('/admin/game/event/missions/items/info', [
    'middleware' => ['require-admin-view'],
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->getShopItemInfo()
]);

// ROTAS DE TESTE - q por incrivel q pareça funcionou e as reais nao 
$router->post('/test/update-item/{activityType}/{subActivityType}/{templateId}', [
    fn ($activityType, $subActivityType, $templateId) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->testUpdateItem($activityType, $subActivityType, $templateId)
]);

$router->post('/test/delete-item/{activityType}/{subActivityType}/{templateId}', [
    fn ($activityType, $subActivityType, $templateId) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->testDeleteItem($activityType, $subActivityType, $templateId)
]);

$router->get('/test/debug-routes', [
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->debugRoutes()
]);

//reset proogress
$router->post('/admin/game/event/missions/{activityType}/reset-progress', [
    'middleware' => ['require-admin-view'],
    fn ($activityType) => (new \App\Http\Controllers\Web\Admin\Events\Mission())->resetProgress($activityType)
]);

$router->get('/admin/game/event/missions/check-progress', [
    'middleware' => ['require-admin-view'],
    fn () => (new \App\Http\Controllers\Web\Admin\Events\Mission())->checkProgress()
]);

#Fim rota das missions - amém

$router->get('/admin/game/map', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Game\Map())->index($id))
]);
$router->get('/admin/game/shop', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Game\Shop())->index($id))
], 'admin.game.shop');
$router->get('/admin/game/config', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Game\Config())->index($id))
]);
$router->get('/admin/game/event/activity', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Events\Activity())->index($id))
]);
$router->get('/admin/game/event/activities', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Events\Activities())->index($id))
]);
$router->get('/admin/game/event/gm-activity', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Events\GmActivity())->index($id))
]);
$router->get('/admin/game/event/activity-system', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Events\ActivitySystem())->index($id))
]);

$router->get('/admin/game/utils/item/send', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Item())->send())
]);
$router->get('/admin/game/utils/recharge/send', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Product())->send())
]);
$router->get('/admin/game/utils/message/send', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Server())->message())
]);

$router->get('/admin/game/announcements', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Game\Announcements())->index($id))
]);

$router->get('/admin/game/pve', [
  'middleware' => [
    'require-admin-view'
  ],
  fn ($id) => new Response(200, (new Admin\Game\Pve())->index($id))
]);

$router->get('/admin/blog/criar', [
  'middleware' => [
    'require-admin-view'
  ],
  fn () => new Response(200, (new Admin\Blog())->create())
]);

