<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMessages extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dbo.User_Messages';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'SenderID' ,
        'Sender' ,
        'ReceiverID' ,
        'Receiver' ,
        'Title' ,
        'Content' ,
        'SendTime' ,
        'IsRead' ,
        'IsDelR' ,
        'IfDelS' ,
        'IsDelete' ,
        'Annex1' ,
        'Annex2' ,
        'Annex3' ,
        'Annex4' ,
        'Annex5' ,
        'Gold' ,
        'Money' ,
        'IsExist',
        'Type',
        'Remark'
      ];
}
