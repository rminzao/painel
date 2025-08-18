<?php

use Core\Routing\Response;
use App\Http\Controllers\Api;
use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Client;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
*/

$router->post('/api/auth/signin', [
  'middleware' => [
    'api'
  ],
  function () {
    return new Response(201, (new Api\Auth())->login(), 'application/json');
  },
]);

$router->post('/api/auth/mobile/signin', [
  function () {
    return new Response(201, (new Api\Auth())->flashLogin());
  },
]);

$router->post('/api/auth/signup', [
  'middleware' => [
    'api'
  ],
  function () {
    return new Response(200, (new Api\Auth())->register(), 'application/json');
  },
]);

$router->post('/api/auth/mobile/signup', [
  function () {
    return new Response(201, (new Api\Auth())->flashRegister());
  },
]);

$router->get('/api/auth/confirm/{hash}', [
  'middleware' => [
    'api'
  ],
  function ($request, $hash) {
    return new Response(200, (new Api\Auth())->setMailConfirm($request, $hash), 'application/json');
  }
]);

$router->post('/api/auth/google', [
  'middleware' => [
    'api'
  ],
  function ($request) {
    return new Response(200, (new Api\Auth())->setAuthGoogle($request), 'application/json');
  },
]);

$router->get('/api/auth/discord', [
  'middleware' => [
    'api'
  ],
  function ($request) {
    return new Response(200, (new Api\Auth())->setAuthDiscord($request), 'application/json');
  },
]);

$router->post('/api/auth/forget', [
  'middleware' => [
    'api'
  ],
  function ($request) {
    return new Response(200, (new Api\Auth())->forget($request), 'application/json');
  },
]);

$router->post('/api/auth/forget/confirm', [
  'middleware' => [
    'api'
  ],
  function ($request) {
    return new Response(200, (new Api\Auth())->forgetConfirm($request), 'application/json');
  },
]);

$router->post('/api/ticket/new', [
  'middleware' => [
    'api',
    'check-unlogged-user'
  ],
  function ($request) {
    return new Response(200, (new Api\Ticket())->setTicket($request), 'application/json');
  }
]);

$router->post('/api/ticket/state', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Api\Ticket())->setState($request), 'application/json');
  }
]);

$router->post('/api/ticket/response/{ticketID}', [
  'middleware' => [
    'api',
    'check-unlogged-user'
  ],
  function ($request, int $ticketID) {
    return new Response(200, (new Api\Ticket())->commentTicket($request, $ticketID), 'application/json');
  }
]);

$router->delete('/api/ticket/response/{ticketID}', [
  'middleware' => [
    'api',
    'check-unlogged-user'
  ],
  function ($request, int $ticketID) {
    return new Response(200, (new Api\Ticket())->deleteTicket($request, $ticketID), 'application/json');
  }
]);

$router->get('/api/product/detail/{id}', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function ($id) {
    return new Response(200, (new Api\Product())->getDetail($id), 'application/json');
  }
]);

$router->post('/api/product/checkCode', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function ($request) {
    return new Response(200, (new Api\Product())->checkCode($request), 'application/json');
  }
]);

$router->get('/api/invoice/list', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function () {
    return new Response(200, (new Api\App\Invoice())->list(), 'application/json');
  }
]);

$router->get('/api/invoice/create', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function () {
    return new Response(200, (new Api\App\Invoice())->create(), 'application/json');
  }
]);

$router->get('/api/ranking', [
  'middleware' => [
    'api',
    'api-check-unlogged-user',
    'cache'
  ],
  fn ($request) => new Response(200, (new Api\Ranking())->list($request), 'application/json')
]);

$router->get('/api/ranking/lobby', [
  'middleware' => [
    'api',
    'api-check-unlogged-user',
    'cache'
  ],
  fn ($request) =>new Response(200, (new Api\Ranking())->lobbyRanking($request), 'application/json')
]);

$router->get('/api/server/config/{id}', [
  'middleware' => [
    'api-check-unlogged-user',
    'cache'
  ],
  function ($request, $id) {
    return new Response(200, (new Api\App\Server())->config($request, $id), 'application/xml');
  }
]);

$router->post('/api/play/server/{id}', [
  'middleware' => [
    'api',
    'check-unlogged-user'
  ],
  function ($id) {
    return new Response(200, (new Api\Play())->info($id), 'application/json');
  }
]);

$router->post('/api/me/account/settings/change_pass', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function ($request) {
    return new Response(200, (new Api\App\Account())->setPassChange($request), 'application/json');
  }
]);

$router->post('/api/me/account/settings/change_mail', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function ($request) {
    return new Response(200, (new Api\App\Account())->setMailChange($request), 'application/json');
  }
]);

$router->post('/api/me/account/settings/change_profile', [
  'middleware' => [
    'api',
    'api-check-unlogged-user'
  ],
  function ($request) {
    return new Response(200, (new Api\App\Account())->setProfileChange($request), 'application/json');
  }
]);

$router->get('/api/account/mail/confirm/{hash}', [
  'middleware' => [
    'api',
  ],
  function ($hash) {
    return new Response(200, (new Api\App\Account())->setMailChangeConfirm($hash), 'application/json');
  }
]);

$router->get('/api/account/referrals', [
  'middleware' => [
    'api',
  ],
  fn ($hash) => new Response(200, (new Api\App\Account())->getReferenced(), 'application/json')
]);

#region Recharge
$router->get('/api/recharge/{method}/notification', [
  'middleware' => [
    'api'
  ],
  function ($request, $method) {
    return new Response(200, (new Api\Recharge())->setNotification($request, $method), 'application/json');
  }
]);

$router->post('/api/recharge/{method}/notification', [
  'middleware' => [
    'api'
  ],
  function ($request, $method) {
    return new Response(200, (new Api\Recharge())->setNotification($request, $method), 'application/json');
  }
]);

// $router->get('/api/recharge/{method}/check', [
//   'middleware' => [
//     'api',
//     //'api-check-unlogged-user'
//   ],
//   fn ($method) =>  new Response(200, (new Api\Recharge())->checkStatus($method), 'application/json')
// ]);

#endregion


/*
|--------------------------------------------------------------------------
| Api admin Routes
|--------------------------------------------------------------------------
|
*/

$router->post('/api/admin/play/server/{id}/{uid}', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($id, $uid) {
    return new Response(200, (new Api\Play())->getPlayerInfoByAdmin($id, $uid), 'application/json');
  }
]);

$router->post('/api/admin/server/message/send', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Server())->setSendMessage($request), 'application/json');
  }
]);

#region serverlist
$router->get('/api/admin/server', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Server())->list($request), 'application/json')
]);

$router->post('/api/admin/server', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Server())->create($request), 'application/json')
]);

$router->put('/api/admin/server', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Server())->update($request), 'application/json')
]);

$router->delete('/api/admin/server', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Server())->delete($request), 'application/json')
]);
#endregion

#region Product
$router->get('/api/admin/product', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product())->list($request), 'application/json')
]);

$router->post('/api/admin/product', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product())->create($request), 'application/json')
]);

$router->put('/api/admin/product', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product())->update($request), 'application/json')
]);

$router->delete('/api/admin/product', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) =>  new Response(200, (new Admin\Product())->delete($request), 'application/json')
]);

$router->post('/api/admin/product/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product())->duplicate($request), 'application/json')
]);

$router->post('/api/admin/product/send', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product())->send($request), 'application/json')
]);

$router->get('/api/admin/product/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\ProductReward())->list($request), 'application/json')
]);

$router->post('/api/admin/product/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\ProductReward())->create($request), 'application/json')
]);

$router->put('/api/admin/product/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\ProductReward())->update($request), 'application/json')
]);

$router->delete('/api/admin/product/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) =>  new Response(200, (new Admin\ProductReward())->delete($request), 'application/json')
]);
#endregion

$router->get('/api/admin/users/equip/list', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Equip())->getList($request), 'application/json');
  }
]);

$router->get('/api/admin/users/all', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Equip())->getAllUsers($request), 'application/json');
  }
]);

$router->post('/api/admin/users/equip/role', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Equip())->changeRole($request), 'application/json');
  }
]);

$router->post('/api/admin/server/{server}/users', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request, $server) {
    return new Response(200, (new Admin\Server())->getUsers($request, $server), 'application/json');
  }
]);

$router->get('/api/admin/item', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->find($request), 'application/json');
  }
]);

$router->put('/api/admin/item', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->update($request), 'application/json');
  }
]);

$router->post('/api/admin/item', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Item())->create($request), 'application/json')
]);

$router->post('/api/admin/item/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Item())->duplicate($request), 'application/json')
]);

$router->delete('/api/admin/item', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Item())->delete($request), 'application/json')
]);

$router->get('/api/admin/item/game/update', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->onGameUpdate($request), 'application/json');
  }
]);


$router->get('/api/admin/item/box', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\ItemBox())->find($request), 'application/json');
  }
]);

$router->post('/api/admin/item/box', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\ItemBox())->create($request), 'application/json');
  }
]);

$router->put('/api/admin/item/box', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\ItemBox())->update($request), 'application/json');
  }
]);

$router->delete('/api/admin/item/box', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\ItemBox())->delete($request), 'application/json');
  }
]);

$router->get('/api/admin/item/list', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->list($request), 'application/json');
  }
]);

$router->get('/api/admin/item/categories', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->getCategoryList($request), 'application/json');
  }
]);

$router->post('/api/admin/item/info', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
    'cache',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->getInfo($request), 'application/json');
  }
]);

$router->post('/api/admin/item/send', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function ($request) {
    return new Response(200, (new Admin\Item())->send($request), 'application/json');
  }
]);

#region game quest
$router->get('/api/admin/game/quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Quest())->list(), 'application/json')
]);

$router->post('/api/admin/game/quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Quest())->create(), 'application/json')
]);

$router->put('/api/admin/game/quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Quest())->update(), 'application/json')
]);

$router->delete('/api/admin/game/quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Quest())->delete(), 'application/json')
]);


$router->get('/api/admin/game/quest/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  function () {
    return new Response(200, (new Admin\Game\Quest())->onGameUpdate(), 'application/json');
  }
]);

$router->get('/api/admin/game/quest/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Quest())->duplicate(), 'application/json')
]);

$router->get('/api/admin/game/quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestConditions())->list(), 'application/json')
]);

$router->post('/api/admin/game/quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestConditions())->create(), 'application/json')
]);

$router->put('/api/admin/game/quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestConditions())->update(), 'application/json')
]);

$router->delete('/api/admin/game/quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestConditions())->delete(), 'application/json')
]);


$router->get('/api/admin/game/quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestGoods())->list(), 'application/json')
]);

$router->post('/api/admin/game/quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestGoods())->create(), 'application/json')
]);

$router->put('/api/admin/game/quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestGoods())->update(), 'application/json')
]);

$router->delete('/api/admin/game/quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\QuestGoods())->delete(), 'application/json')
]);
#endregion


$router->get('/api/admin/drop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->list(), 'application/json')
]);

$router->post('/api/admin/drop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->create(), 'application/json')
]);

$router->put('/api/admin/drop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->update(), 'application/json')
]);

$router->delete('/api/admin/drop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->delete(), 'application/json')
]);

$router->get('/api/admin/drop/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->updateOnGame(), 'application/json')
]);

$router->post('/api/admin/drop/pve-import', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->pveImport(), 'application/json')
]);

$router->post('/api/admin/drop/import', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->import(), 'application/json')
]);

$router->get('/api/admin/drop/export', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Drop())->export(), 'application/json')
]);

$router->get('/api/admin/drop/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\DropItem())->list($request), 'application/json')
]);

$router->post('/api/admin/drop/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\DropItem())->create($request), 'application/json')
]);

$router->put('/api/admin/drop/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\DropItem())->update($request), 'application/json')
]);

$router->delete('/api/admin/drop/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\DropItem())->delete($request), 'application/json')
]);

$router->get('/api/admin/invoice', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Invoice())->list($request), 'application/json')
]);

$router->post('/api/admin/invoice', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Invoice())->create($request), 'application/json')
]);

$router->put('/api/admin/invoice', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Invoice())->update($request), 'application/json')
]);

$router->get('/api/admin/user/{id}/detail', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request, $id) => new Response(200, (new Admin\User())->detail($request, $id), 'application/json')
]);

$router->get('/api/admin/web/user', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\User())->web(), 'application/json')
]);

$router->get('/api/admin/user', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\User())->list($request), 'application/json')
]);

$router->put('/api/admin/user', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\User())->update($request), 'application/json')
]);

$router->put('/api/admin/user/password', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\User())->updatePassword($request), 'application/json')
]);

$router->put('/api/admin/user/email', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\User())->updateEmail($request), 'application/json')
]);

$router->get('/api/admin/user/game/message', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Message())->list($request), 'application/json')
]);

$router->get('/api/admin/user/game/bag', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User\Bag())->list($request), 'application/json')
]);

$router->get('/api/admin/game/user', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User())->list($request), 'application/json')
]);

$router->get('/api/admin/game/user/disconnect', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User())->disconnect($request), 'application/json')
]);

$router->put('/api/admin/user/game/nick', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User())->updateNickname($request), 'application/json')
]);

$router->post('/api/admin/user/game/forbid', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User())->forbid($request), 'application/json')
]);

$router->get('/api/admin/user/ranking-update', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\User())->updateRanking($request), 'application/json')
]);


#region game events
$router->get('/api/admin/game/event/activities', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event())->list($request), 'application/json')
]);

$router->get('/api/admin/game/event/activities/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event())->updateOnGame($request), 'application/json')
]);

$router->get('/api/admin/game/event/activities/conditions', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventCondition())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activities/conditions', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventCondition())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activities/conditions', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventCondition())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activities/conditions', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventCondition())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/event/activities/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventGoods())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activities/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventGoods())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activities/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventGoods())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activities/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\EventGoods())->delete($request), 'application/json')
]);
#endregion

#region Activity
$router->get('/api/admin/game/event/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->delete($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->duplicate($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity/reset', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->reset($request), 'application/json')
]);

$router->get('/api/admin/game/event/activity/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Activity())->updateOnGame($request), 'application/json')
]);

$router->get('/api/admin/game/event/activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivityAward())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivityAward())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivityAward())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivityAward())->delete($request), 'application/json')
]);
#endregion

#region serverConfig (game)
$router->get('/api/admin/game/server/config', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ServerConfig())->list($request), 'application/json')
]);

$router->post('/api/admin/game/server/config', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ServerConfig())->create($request), 'application/json')
]);

$router->put('/api/admin/game/server/config', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ServerConfig())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/server/config', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ServerConfig())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/server/config/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ServerConfig())->updateOnGame($request), 'application/json')
]);
#endregion

#region suit
$router->get('/api/admin/game/suit', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Suit())->list($request), 'application/json')
]);

$router->post('/api/admin/game/suit', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Suit())->create($request), 'application/json')
]);

$router->put('/api/admin/game/suit', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Suit())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/suit', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Suit())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/suit/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Suit())->updateOnGame($request), 'application/json')
]);

$router->get('/api/admin/game/suit/part', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitEquip())->list($request), 'application/json')
]);

$router->post('/api/admin/game/suit/part', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitEquip())->create($request), 'application/json')
]);

$router->put('/api/admin/game/suit/part', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitEquip())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/suit/part', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitEquip())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/suit/skill', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitSkills())->list($request), 'application/json')
]);

$router->post('/api/admin/game/suit/skill', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitSkills())->create($request), 'application/json')
]);

$router->put('/api/admin/game/suit/skill', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitSkills())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/suit/skill', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\SuitSkills())->delete($request), 'application/json')
]);
#endregion

#region shop
$router->get('/api/admin/game/shop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Shop())->list($request), 'application/json')
], 'api.admin.game.shop');

$router->post('/api/admin/game/shop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Shop())->create($request), 'application/json')
], 'api.admin.game.shop');

$router->put('/api/admin/game/shop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Shop())->update($request), 'application/json')
], 'api.admin.game.shop');

$router->delete('/api/admin/game/shop', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Shop())->delete($request), 'application/json')
], 'api.admin.game.shop');

$router->get('/api/admin/game/shop/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Shop())->updateOnGame($request), 'application/json')
], 'api.admin.game.shop');

$router->get('/api/admin/game/shop/show', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ShopGoodsShowList())->list($request), 'application/json')
], 'api.admin.game.shop');

$router->post('/api/admin/game/shop/show', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ShopGoodsShowList())->create($request), 'application/json')
], 'api.admin.game.shop');

$router->put('/api/admin/game/shop/show', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ShopGoodsShowList())->update($request), 'application/json')
], 'api.admin.game.shop');

$router->delete('/api/admin/game/shop/show', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ShopGoodsShowList())->delete($request), 'application/json')
], 'api.admin.game.shop');

#endregion

#region ActivitySystem
$router->get('/api/admin/game/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivitySystem())->list($request), 'application/json')
]);

$router->post('/api/admin/game/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivitySystem())->create($request), 'application/json')
]);

$router->put('/api/admin/game/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivitySystem())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\ActivitySystem())->delete($request), 'application/json')
]);

#endregion

#region GmActivity
$router->get('/api/admin/game/gm-activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->list($request), 'application/json')
]);

$router->post('/api/admin/game/gm-activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->create($request), 'application/json')
]);

$router->put('/api/admin/game/gm-activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/gm-activity', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/gm-activity/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->duplicate($request), 'application/json')
]);

$router->get('/api/admin/game/gm-activity/reset', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->reset($request), 'application/json')
]);

$router->get('/api/admin/game/gm-activity/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivity())->updateOnGame($request), 'application/json')
]);
#endregion

$router->get('/api/admin/game/gm-activity/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityCondition())->list($request), 'application/json')
]);

$router->post('/api/admin/game/gm-activity/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityCondition())->create($request), 'application/json')
]);

$router->put('/api/admin/game/gm-activity/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityCondition())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/gm-activity/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityCondition())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/gm-activity/gift', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmGift())->list($request), 'application/json')
]);

$router->post('/api/admin/game/gm-activity/gift', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmGift())->create($request), 'application/json')
]);

$router->put('/api/admin/game/gm-activity/gift', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmGift())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/gm-activity/gift', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmGift())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/gm-activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityReward())->list($request), 'application/json')
]);

$router->post('/api/admin/game/gm-activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityReward())->create($request), 'application/json')
]);

$router->put('/api/admin/game/gm-activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityReward())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/gm-activity/rewards', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\GmActivityReward())->delete($request), 'application/json')
]);

$router->get('/api/admin/product/code', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product\Code())->list($request), 'application/json')
]);

$router->post('/api/admin/product/code', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product\Code())->create($request), 'application/json')
]);

$router->put('/api/admin/product/code', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product\Code())->update($request), 'application/json')
]);

$router->delete('/api/admin/product/code', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Product\Code())->delete($request), 'application/json')
]);

$router->post('/api/admin/game/user/quest/complete', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User\QuestData())->complete($request), 'application/json')
]);

$router->post('/api/admin/game/user/laboratory/complete', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\User())->completeLaboratory($request), 'application/json')
]);

$router->get('/api/admin/game/map', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Map())->list(), 'application/json')
]);
$router->post('/api/admin/game/map', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Map())->create(), 'application/json')
]);
$router->put('/api/admin/game/map', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Map())->update(), 'application/json')
]);
$router->delete('/api/admin/game/map', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Map())->delete(), 'application/json')
]);


$router->get('/api/admin/game/announcement', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Announcement())->list(), 'application/json')
]);

$router->post('/api/admin/game/announcement', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Announcement())->create(), 'application/json')
]);
$router->put('/api/admin/game/announcement', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Announcement())->update(), 'application/json')
]);


$router->delete('/api/admin/game/announcement', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Announcement())->delete(), 'application/json')
]);


$router->get('/api/admin/game/event/activity-quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuest())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity-quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuest())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activity-quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuest())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activity-quest', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuest())->delete($request), 'application/json')
]);

$router->get('/api/admin/game/event/activity-quest/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuest())->updateOnGame($request), 'application/json')
]);

$router->get('/api/admin/game/event/activity-quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(
    200,
    (new Admin\Game\Event\ActivityQuestcondition())->list($request),
    'application/json'
  )
]);

$router->post('/api/admin/game/event/activity-quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(
    200,
    (new Admin\Game\Event\ActivityQuestcondition())->create($request),
    'application/json'
  )
]);

$router->put('/api/admin/game/event/activity-quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(
    200,
    (new Admin\Game\Event\ActivityQuestcondition())->update($request),
    'application/json'
  )
]);

$router->delete('/api/admin/game/event/activity-quest/condition', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(
    200,
    (new Admin\Game\Event\ActivityQuestcondition())->delete($request),
    'application/json'
  )
]);

$router->get('/api/admin/game/event/activity-quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuestReward())->list($request), 'application/json')
]);

$router->post('/api/admin/game/event/activity-quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuestReward())->create($request), 'application/json')
]);

$router->put('/api/admin/game/event/activity-quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuestReward())->update($request), 'application/json')
]);

$router->delete('/api/admin/game/event/activity-quest/reward', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn ($request) => new Response(200, (new Admin\Game\Event\ActivityQuestReward())->delete($request), 'application/json')
]);

#region PVE System
$router->get('/api/admin/game/pve', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->list(), 'application/json')
]);

$router->post('/api/admin/game/pve', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->create(), 'application/json')
]);

$router->put('/api/admin/game/pve', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->update(), 'application/json')
]);

$router->delete('/api/admin/game/pve', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->delete(), 'application/json')
]);

$router->get('/api/admin/game/pve/suggested-id', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->getSuggestedId(), 'application/json')
]);

$router->get('/api/admin/game/pve/available-items', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->getAvailableItems(), 'application/json')
]);

$router->post('/api/admin/game/pve/update-ordering', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->updateOrdering(), 'application/json')
]);

$router->get('/api/admin/game/pve/types', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->getTypes(), 'application/json')
]);

$router->get('/api/admin/game/pve/update-on-game', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->updateOnGame(), 'application/json')
]);

$router->post('/api/admin/game/pve/import', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->import(), 'application/json')
]);

$router->get('/api/admin/game/pve/export', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->export(), 'application/json')
]);

$router->post('/api/admin/game/pve/duplicate', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Game\Pve())->duplicate(), 'application/json')
]);
#endregion

$router->post('/api/admin/blog', [
  'middleware' => [
    'api',
    'jwt-auth',
    'admin',
  ],
  fn () => new Response(200, (new Admin\Blog())->create(), 'application/json')
]);
