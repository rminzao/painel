<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\Api;
use App\Models\Server as ModelsServer;
use Core\Routing\Request;

use function GuzzleHttp\default_ca_bundle;

class Server extends Api
{
    public function config(Request $request, $id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            http_response_code(404);
            exit;
        }

        $server = ModelsServer::find($id);
        if (!$server) {
            http_response_code(404);
            exit;
        }

        $server->settings = json_decode(unserialize($server['settings']));
        
        $request->getRouter()->setContentType('application/xml');
        

        return match(true)
        {
            $server->version >= 12000 => view('others.configs.12000', [
                 'server' => $server
            ]),
            $server->version > 5500 && $server->version < 12000 => view('others.configs.11000', [
                'server' => $server
            ]),
            $server->version > 4100 && $server->version <= 5500 => view('others.configs.5500', [
                'server' => $server
            ]),
            default =>view('others.configs.4100', [
                 'server' => $server
            ]),
        };
   
    }
}
