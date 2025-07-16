<?php

namespace Core\Utils\Game;

abstract class ReloadType
{
    public const BALL = 'Ball';
    public const MAP = 'Map';
    public const MAP_SERVER = 'MapServer';
    public const DROP = 'Drop';
    public const FALL_DROP = 'FallProp';
    public const PROP = 'Prop';
    public const ITEM = 'Item';
    public const QUEST = 'Quest';
    public const FUSION = 'Fusion';
    public const SERVER_CONFIG = 'ServerProperties';
    public const SERVER_LIST = 'ServerList';
    public const RATE = 'Rate';
    public const CONSORTIA = 'Consortia';
    public const SHOP = 'Shop';
    public const LEVEL = 'Level';
    public const FIGHT_RATE = 'FightRateMgr';
    public const DAILY_AWARD = 'DailyAward'; #Recompensas Diárias
    public const LANGUAGE = 'Language';
    public const FIGTH_SPIRIT_TEMPLATE = 'FightSpiritTemplate';
    public const PET_MOE_PROPERTY = 'PetMoeProperty';
    public const ACTIVITY_WONDER = 'WonderFullActivity'; #GmActivity
    public const ACTIVITY_NOVICE = 'NoviceActivity'; #Atividade e Coleta
    public const ACTIVITY_SYSTEM = 'ActivitySytem'; #Atividade do sistema (prâmide, Crescimento etc)
    public const TOTEM = 'Totem';
    public const TOTEM_HONOR = 'TotemHonor';
}
