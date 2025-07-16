<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_comments';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = ['ticket_id', 'content', 'uid'];
}
