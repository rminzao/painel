<?php

namespace App\Http\Controllers\Api;

use App\Models\Ticket as ModelsTicket;
use App\Models\TicketComment;
use Core\Routing\Request;
use Core\Utils\Upload;

class Ticket extends Api
{
    public function setState(Request $request)
    {
        $post = $request->post();

        $ticket = ModelsTicket::find($post['tid']);
        $ticket->status = $post['status'];

        if (!$ticket->save()) {
            return [
                'state' => false,
                'message' => 'falha ao alterar status do ticket.',
            ];
        }

        return [
            'state' => true,
            'message' => 'Status alterado com sucesso.',
        ];
    }

    public function setTicket(Request $request)
    {
        $attachments = null;

        if (!empty($_FILES['file'])) {
            $files = $_FILES['file'];

            if (count($files['type']) > 4) {
                return [
                'state' => false,
                'message' => 'Você só pode enviar 4 imagens no máximo.'
                ];
            }

            for ($i = 0; $i < count($files['type']); $i++) {
                foreach (array_keys($files) as $keys) {
                    $images[$i][$keys] = $files[$keys][$i];
                }
            }

            $upload = new Upload();
            foreach ($images as $item) {
                $image = $upload->image($item, "ticket-" . uuid(), 'images/ticket');
                if (!$image) {
                    return [
                        'state' => false,
                        'message' => $upload->message()->render()
                    ];
                }
                $name = explode('/', $image);
                $attachmentsList[] = end($name);
            }
            $attachments = implode(',', $attachmentsList);
        }

        $postVars = $request->post();
        $title = $postVars['title'] ?? '';
        $content = $postVars['content'] ?? '';

        if (!$title or !$content) {
            return [
            'state' => false,
            'message' => 'O [assunto] e a [mensagem] são obrigatórios'
            ];
        }

        $model = new ModelsTicket();
        $ticket = $model::create([
        'title' => strip_tags($title),
        'content' => strip_tags($content),
        'uid' => $this->user->id,
        'images' => $attachments
        ]);
        if (is_null($ticket->id)) {
            return [
            'state' => false,
            'message' => 'Whoops... Ocorreu um erro interno ao registrar seu chamado, tente novamente mais tarde.'
            ];
        }

        return [
        'state' => true,
        'message' => 'Chamado registrado com sucesso, iremos te responder em breve.',
        'url' => url("app/me/ticket/detail/{$ticket->id}")
        ];
    }

    public function commentTicket(Request $request, $ticketID)
    {
        $postVars = $request->post();

        if (!isset($postVars['content'])) {
            return [
                'state' => false,
                'message' => 'Prêncha os Campos'
            ];
        }

        if (!$ticket = ModelsTicket::find($ticketID)) {
            return [
                'state' => false,
                'message' => 'Ticket Não Encontrado'
            ];
        }

        if ($ticket->status == 'closed') {
            return [
                'state' => false,
                'message' => 'Esse Ticket ja foi fechado!'
            ];
        }


        if ($ticket->uid != $this->user->id && $this->user->role < 2) {
            return [
                'state' => false,
                'message' => 'você não pode responder esse Ticket'
            ];
        }

        if (
            TicketComment::create([
            'ticket_id' => $ticketID,
            'content' => $postVars['content'],
            'uid' => $this->user->id
            ])
        ) {
            return [
                'state' => true,
                'message' => 'Novo Comentario Publicado',
                'url' => url("app/me/ticket/detail/{$ticket->id}")
            ];
        } else {
            return [
                'state' => false,
                'message' => 'Erro ao Publicar Comentario'
            ];
        }
    }

    public function deleteTicket(Request $request, $ticketID)
    {
        if (!$ticket = TicketComment::find($ticketID)) {
            return [
                'state' => false,
                'message' => 'Comentário Não Encontrado'
            ];
        }


        if ($ticket->uid != $this->user->id && $this->user->role < 3) {
            return [
                'state' => false,
                'message' => 'você não pode responder esse Ticket'
            ];
        } else {
            if ($ticket->delete()) {
                return [
                    'state' => true,
                    'message' => 'Deletado Com Sucesso!'
                ];
            } else {
                return [
                    'state' => false,
                    'message' => 'Erro ao deletar Comentário!'
                ];
            }
        }
    }
}
