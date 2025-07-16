<?php

namespace App\Models;

use Core\Utils\Wsdl;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = '.dbo.Sys_Users_Detail';

    protected $tableGoods = '.dbo.Sys_Users_Goods';

    protected $primaryKey = 'UserID';

    protected $hidden = ['QuestSite', 'WeaklessGuildProgressStr'];

    protected $guarded = [];

    public $timestamps = false;

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->table = $database . $this->table;
            $this->tableGoods = $database . $this->tableGoods;
        }
    }

    public function goods(?int $id = null, ?int $uid = null)
    {
        $this->primaryKey = 'ItemID';
        $this->table = $this->tableGoods;

        return (!$id && !$uid) ? $this : (!$uid ? ($this)->find($id) : ($this)->where('UserID', $uid));
    }

    public function findByUser(string $user, $columns = "*")
    {
        return $this->select($columns)->where('UserName', $user)->get();
    }

    public function disconnect($server)
    {
        if ($this->State == 0) {
            return true;
        }

        if ($server->wsdl == '') {
            return false;
        }

        $wsdl = new Wsdl($server->wsdl);
        $wsdl->method = 'KitoffUser';
        $wsdl->paramters = [
          "playerID" => (int) $this->UserID,
          "msg" => "Você foi desconectado do servidor, pela administração do sistema."
        ];

        if (!$wsdl->send()) {
            return false;
        }

        sleep(1);
        return true;
    }
}
