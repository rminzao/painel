<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopGoods extends Model
{
    protected $table = '.dbo.Shop_Goods';

    protected $gameBase = '';

    protected $primaryKey = 'TemplateID';

    public $timestamps = false;

    protected $hidden = [];

    protected $guarded = [];

    public function __construct(?string $database = null)
    {
        if ($database != null) {
            $this->gameBase = $database;
            $this->table = $database . $this->table;
        }
    }

    public function detail(bool $array = true)
    {
        $data = [
          'Name' => $this->Name,
          'CategoryID' => $this->CategoryID,
          'MaxCount' => $this->MaxCount,
          'NeedSex' => $this->NeedSex,
          'CanCompose' => $this->CanCompose,
          'CanStrengthen' => $this->CanStrengthen
        ];
        return $array ? $data : (object) $data;
    }

    public function image()
    {
        $specialList = [
          10001 => '2x',
          10002 => '1x',
          10003 => 'x3',
          10004 => '50',
          10005 => '40',
          10006 => '30',
          10007 => '20',
          10008 => '10',
          10009 => 'life300',
          10010 => 'hidden',
          10011 => 'hiddenTeam',
          10012 => 'life500',
          10013 => 'changeWind',
          10015 => 'ice',
          10016 => 'fly',
          10017 => 'missile',
          10018 => 'anger',
          10020 => 'piercing',
          10021 => 'avoidHole',
          10022 => 'atomic',
          10024 => '10024',
          10025 => 'fly',
        ];

        if (array_key_exists($this->TemplateID, $specialList)) {
            return url('assets/media/game/battle/' . $specialList[$this->TemplateID] . '.png');
        }

        $sex = $this->NeedSex;
        $cat = (int) $this->CategoryID;
        $pic = $this->Pic;

        $resUrl = $_ENV['APP_RESOURCE'];

        $sexChang = match ($sex) {
            '1' => 'm',
            '2' => 'f',
            default => 'f',
        };

        $img = match ($cat) {
            1 => "equip/$sexChang/head/$pic/icon_1.png",
            2 => "equip/$sexChang/glass/$pic/icon_1.png",
            3 => "equip/$sexChang/hair/$pic/icon_1.png",
            4 => "equip/$sexChang/eff/$pic/icon_1.png",
            5 => "equip/$sexChang/cloth/$pic/icon_1.png",
            6 => "equip/$sexChang/face/$pic/icon_1.png",
            7 => "arm/$pic/00.png",
            8, 28 => "equip/armlet/$pic/icon.png",
            9, 29 => "equip/ring/$pic/icon.png",
            11 => "unfrightprop/$pic/icon.png",
            12 => "task/$pic/icon.png",
            13 => "equip/$sexChang/suits/$pic/icon_1.png",
            14 => "equip/necklace/$pic/icon.png",
            15 => "equip/wing/$pic/icon.png",
            16 => "specialprop/chatBall/$pic/icon.png",
            17 => "equip/offhand/$pic/icon.png",
            18 => "cardbox/$pic/icon.png",
            19 => "equip/recover/$pic/icon.png",
            20 => "unfrightprop/$pic/icon.png",
            23 => "unfrightprop/$pic/icon.png",
            25 => "gift/$pic/icon.png",
            26 => "card/$pic/icon.jpg",
            27 => "arm/$pic/00.png",
            30 => "unfrightprop/$pic/icon.png",
            31 => "equip/offhand/$pic/icon.png",
            32 => "farm/Crops/$pic/seed.png",
            33 => "farm/fertilizer/$pic/icon.png",
            34 => "unfrightprop/$pic/icon.png",
            35 => "unfrightprop/$pic/icon.png",
            36 => "unfrightprop/$pic/icon.png",
            40 => "unfrightprop/$pic/icon.png",
            50 => "petequip/arm/$pic/icon.png",
            51 => "petequip/hat/$pic/icon.png",
            52 => "petequip/cloth/$pic/icon.png",
            62 => "unfrightprop/$pic/icon.png",
            64 => "arm/$pic/00.png",
            66 => "cardbox/$pic/icon.png",
            68 => "unfrightprop/$pic/icon.png",
            69 => "unfrightprop/$pic/icon.png",
            70 => "equip/amulet/$pic/1/icon.png",
            72 => "unfrightprop/$pic/icon.png",
            63, 73 => "prop/$pic/icon.png",
            74 => "rune/$pic.png",
            87 => "farm/prop/$pic.png",
            24, 37, 60, 61, 53, 78, 79, 71, 23, 30, 40, 39, 84, 85, 86, 89 => "unfrightprop/$pic/icon.png",
            default => false,
        };

        if (!$img) {
            return url('assets/media/icons/original.png');
        }

        return "$resUrl/image/$img";
    }

    public function getByListId(?array $list = [], ?array $columns = [])
    {
        if ($columns == null) {
            $columns = ['TemplateID', 'Name', 'NeedSex'];
        }

        $results = [];

        foreach ($list as $id) {
            if ($id == '') {
                continue;
            }
            if (!$item = $this->select($columns)->where('TemplateID', $id)->first()?->toArray()) {
                $results[] = [
                  'TemplateID' => $id,
                  'Name' => '❓ Desconhecido',
                  'NeedSex' => 0,
                  'Image' => 'https://via.placeholder.com/60',
                ];
                continue;
            };

            $item['Image'] = $this->image();
            $results[] = $item;
        }

        return $results;
    }
}
