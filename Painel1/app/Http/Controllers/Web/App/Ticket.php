<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Web\Controller;
use App\Models\Ticket as EntityTicket;
use App\Models\TicketComment;
use App\Models\User;
use Core\View\Paginator;

class Ticket extends Controller
{
    public function list()
    {
        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        if ($this->user->role > 1) {
            $ticket = (new EntityTicket())->where('status', '=', 'open');
        } else {
            $ticket = (new EntityTicket())->where('uid', '=', $this->user->id);
        }

        $pager = new Paginator(url('app/me/ticket/list?page='));
        $pager->pager($ticket->count(), 10, $page, 2);

        $tickets = $ticket
          ->limit($pager->limit())
          ->offset($pager->offset())
          ->get()
          ?->toArray();

        foreach ($tickets as &$ticket) {
            $comments = TicketComment::where('ticket_id', $ticket['id'])
              ->get()
              ?->toArray();

            $ticket['comments'] = $comments;
        }

        return $this->view->render('app.ticket.list', [
            'tickets' => $tickets,
            'paginator' => $pager->render()
        ]);
    }

    public function detail($id = null)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            redirect('app/me/ticket/list');
        }

        $ticket = EntityTicket::find($id);
        if (!$ticket or ($ticket->uid != $this->user->id and $this->user->role < 2)) {
            redirect('app/me/ticket/list');
        }

        $attachments = [];
        if ($ticket->images != null) {
            $images = explode(',', $ticket->images);
            foreach ($images as $image) {
                $attachments[] = image('ticket/' . $image, 1000);
            }
        }

        $comments = (new TicketComment())
          ->where('ticket_id', $id)
          ->get()
          ?->toArray();

        $commentList = [];

        $users = [];
        foreach ($comments as $comment) {
            if (!isset($users[$comment['uid']])) {
                $users[$comment['uid']] =
                  $comment['uid'] != $this->user->id ? User::find($comment['uid']) : $this->user;
            }

            $commentList[] = array_merge($comment, [
                'receive_id' => $this->user->id,
                'avatar' => image_avatar($users[$comment['uid']]->photo, 50),
                'admin' => $users[$comment['uid']]->role > 1,
                'name' => $users[$comment['uid']]->first_name
            ]);
        }

        return $this->view->render('app.ticket.view', [
            'ticket' => $ticket,
            'comments' => $commentList,
            'attachments' => $attachments,
            'owner' => $ticket->uid == $this->user->id ? $this->user : User::find($ticket->uid)
        ]);
    }

    public function create()
    {
        return $this->view->render('app.ticket.new');
    }
}
