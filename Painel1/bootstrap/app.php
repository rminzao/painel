<?php

/*
|--------------------------------------------------------------------------
| Init Environment
|--------------------------------------------------------------------------
|
*/

(Dotenv\Dotenv::createImmutable(__DIR__ . '/../'))->load();

/*
|--------------------------------------------------------------------------
| Init Errors Handler
|--------------------------------------------------------------------------
|
*/

if ($_ENV['APP_DEBUG'] == 'true') {
    (new \Whoops\Run())
    ->prependHandler(new \Whoops\Handler\PrettyPageHandler())
    ->register();
}

/*
|--------------------------------------------------------------------------
| Init Database
|--------------------------------------------------------------------------
|
*/

Core\Database::init();

/*
|--------------------------------------------------------------------------
| Bind Middleware
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

Core\Routing\Middleware\Queue::setMap([
    'api'                     => \App\Http\Middleware\Api::class,
    'cache'                   => \App\Http\Middleware\Cache::class,
    'jwt-auth'                => \App\Http\Middleware\JWTAuth::class,
    'admin'                   => \App\Http\Middleware\IsAdmin::class,
    'maintenance'             => \App\Http\Middleware\Maintenance::class,
    'browser-check'           => \App\Http\Middleware\BrowserCheck::class,
    'check-logged-user'       => \App\Http\Middleware\UserIsLogged::class,
    'check-unlogged-user'     => \App\Http\Middleware\UserIsUnlogged::class,
    'api-check-unlogged-user' => \App\Http\Middleware\ApiCheckUser::class,
    'require-admin'           => \App\Http\Middleware\RequireAdmin::class,
    'require-developer'       => \App\Http\Middleware\RequireDeveloper::class,
    'require-admin-view'      => \App\Http\Middleware\RequireAdminView::class,
]);

//==> set default middleware
Core\Routing\Middleware\Queue::setDefault([
    'maintenance',
    'browser-check'
]);

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

$router = new Core\Routing\Router($_ENV['APP_URL']);

include __DIR__ . '/../routes/web.php';
include __DIR__ . '/../routes/api.php';

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $router;
