<?php

namespace App\Http\Controllers\Api\Admin\Game\User;

use App\Http\Controllers\Api\Api;
use App\Models\Character;
use App\Models\Game\User\QuestData as UserQuestData;
use App\Models\Server;
use Core\Routing\Request;
use Core\Utils\Wsdl;

class QuestData extends Api
{
    public function complete(Request $request)
    {
        $post = $request->post(false);

        $uid = $post['uid'] ?? null;
        $sid = $post['sid'] ?? null;
        $type = $post['type'] ?? null;

        if (in_array('', $post)) {
            return [
                'status' => false,
                'message' => 'Preencha todos os campos'
            ];
        }

        //find server
        $server =  Server::find($sid);
        if (!$server) {
            return [
                'state' => false,
                'message' => 'Servidor não encontrado'
            ];
        }

        $user = (new Character())
            ->setTable($server->dbUser . '.dbo.Sys_Users_Detail')
            ->where('UserID', $uid)
            ->first();

        if (!$user) {
            return [
                'status' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        if ($user->State) {
            if ($server->wsdl == '') {
                return [
                    'status' => false,
                    'message' => 'O usuário está online e o servidor não possui WSDL, por favor, desconecte-o.'
                ];
            }

            $wsdl = new Wsdl($server->wsdl);
            $wsdl->method = 'KitoffUser';
            $wsdl->paramters = [
                "playerID" => (int) $user->UserID,
                "msg" => "Você foi desconectado do servidor, pela administração do sistema."
            ];
            if (!$wsdl->send()) {
                return [
                    'state' => false,
                    'message' => 'Erro ao desconectar usuário, verifique o WSDL do servidor.'
                ];
            }
            sleep(1);
        }

        $questData = (new UserQuestData())->setTable($server->dbUser . '.dbo.QuestData');

        $query = $questData->where('UserID', $uid);
        $query = $query->whereIn('QuestID', function ($query) use ($server, $type) {
            $type != 'all'
              ? $query->select('ID')->from("{$server->dbData}.dbo.Quest")->whereRaw("QuestID = $type")
              : $query->select('ID')->from("{$server->dbData}.dbo.Quest");
        });

        if (
            !$query->update([
            'Condition1' => 0,
            'Condition2' => 0,
            'Condition3' => 0,
            'Condition4' => 0
            ])
        ) {
            return [
                'status' => false,
                'message' => 'Erro ao definir missão como concluída'
            ];
        }

        return [
            'state' => true,
            'message' => 'Um total de <span class="text-primary">' . $query->count() . ' missões</span> foram definidas como <span class="text-success">concluídas</span>, com sucesso!',
        ];
    }
}
