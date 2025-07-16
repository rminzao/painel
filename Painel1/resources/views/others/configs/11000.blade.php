<?xml version="1.0" encoding="utf-8"?>
<root>
    <config>
        <BACKUP_FLASHSITE value="{{ $server->flash }}/" />
        <USE_MD5 value="{{ $server->settings->md5 == 1 ? 'true' : 'false' }}" />
        <FLASHSITE value="{{ $server->flash }}/" />
        <SITE value="{{ $server->resource }}/" />
        <RESOURCE_SITE value="{{ $server->flash }}/" />
        <FIRSTPAGE value="{{ url() }}/" />
        <REGISTER value="{{ url() }}/" />
        <REQUEST_PATH value="{{ $server->quest }}/" />
        <LOGIN_PATH value="{{ url() }}/" siteName="DDT" />
        <FILL_PATH value="{{ url() }}/" />
        <WEEKLYSITE value="{{ url() }}/" />
        <POLICY_FILES>
            <file value="{{ url() }}/crossdomain.xml" />
        </POLICY_FILES>
        <ALLOW_MULTI value="true" />
        <FIGHTLIB value="true" />
        <TRAINER_PATH value="tutorial.swf" />
        <MUSIC_LIST
            value="1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1023,1024,1025,1026,1027,1028,1029,1030,1031,1032,1034,1035,1036,1037,1038,1039,1040,1059,1060,1061,1062,1063,1065,1067,1068,1069,1077" />
        <LANGUAGE value="{{ $server->settings->lang }}" />
        <PARTER_ID value="7036" />
        <STATISTIC value="true" />
        <SUCIDE_TIME value="120" />
        <ISTOPDERIICT value="true" />
        <COUNT_PATH value="http://assayerhandler.7road.com/" />
        <PHP isShow="false" link="false" site="http://baidu.com/" infoPath="a.xml" />
        <OFFICIAL_SITE value="http://br.337.com/redirect.php?game=ddt" />
        <GAME_FORUM value="http://br.337.com/forum/viewforum.php?f=15" />
        <COMMUNITY_FRIEND_PATH isUser="false" value="http://ddt.the9.com/addfriend.php" />
        <COMMUNITY_INVITE_PATH value="http://ddt.the9.com/invite.php" />
        <COMMUNITY_FRIEND_LIST_PATH value="" isexist="false" />
        <COMMUNITY_FRIEND_INVITED_SWITCH value="false" invitedOnline="false" />
        <COMMUNITY_MICROBLOG value="false" />
        <EXTERNAL_INTERFACE enable="true" path="http://api.mmog.asia/partner/facebook/apps/lib/fb_boomz.php"
            key="cr64rAmUPratutUp" server="t1" />
        <USERS_IMPORT_ACTIVITIES path="http://assist0.ddt.br.337.com/SendMessage.ashx" enable="false" />
        <ALLOW_POPUP_FAVORITE value="true" />
        <FILL_JS_COMMAND value="showPayments" enable="true" />
        <SHIELD_NOTICE value="false" />
        <!-- 铁匠铺强化最高等级开关 -->
        <STHRENTH_MAX value="12" />
        <!-- 客服系统开关 -->
        <FEEDBACK enable="false" />
        <!--3.3之前的新版新手引导开关-->
        <USER_GUILD_ENABLE value="true" />
        <!--远征码头等级限制-->
        <MINLEVELDUPLICATE value="10" />
        <!-- 师徒副本开关 -->
        <TEACHER_PUPIL_FB enable="true" />
        <!-- 公会技能开关 -->
        <GUILD_SKILL enable="true" />
        <LEAGUE enable="true" />
        <!-- 开启温泉房间续费功能-->
        <HOTSPRING value="false" />
        <!-- 公会名称大于等于13字符颜色修改-->
        <CONSORTIA_NAME_CHANGECOLOR enable="true" color="0xFF0000" value="12" />
        <!-- 桌面收藏开关 -->
        <DAILY enable="true" />
        <CLIENT_DOWNLOAD value="http://www.goplayer.cc/public/games/ddt/DDTank.exe" />
        <!-- 修炼系统开关 -->
        <TEXPBTN value="true" />
        <!-- 工会使命系统开关 -->
        <PLACARD_TASKBTN value="true" />
        <!-- 工会图标开关 -->
        <BADGEBTN value="true" />
        <!--下载登录开关-->
        <DOWNLOAD value="true" />
        <!--- 梅恩兰德大陆祝福开关-->
        <!--- 幸运数字的开关-->
        <LUCKY_NUMBER enable="false" />
        <!--- 占卜的开关-->
        <LOTTERY enable="false" />
        <!-- 交友中心和结婚的开关-->
        <MODULE>
            <CIVIL enable="true" />
            <CHURCH enable="true" />
        </MODULE>
        <CHAT_FACE>
            <DISABLED_LIST list="38" />
        </CHAT_FACE>
        <GAME_FRAME_CONFIG>
            <!-- 每帧执行的毫秒数,正常值应为40毫秒,并允许在35至45毫秒之间波动  (游戏内正常帧速为 25帧/秒)-->
            <FRAME_TIME_OVER_TAG value="67" />
            <!-- 连续低于上述毫秒的帧数 (游戏内正常帧速为 25帧/秒) -->
            <FRAME_OVER_COUNT_TAG value="25" />
        </GAME_FRAME_CONFIG>
        <SHORTCUT enable="false" />
        <GAME_BOXPIC value="1" />
        <BUFF enable="true" />
        <LITTLEGAMEMINLV value="10" />
        <SHOW_BACKGROUND value="true" />
        <TRAINER_STANDALONE value="true" />
        <OVERSEAS>
            <OVERSEAS_COMMUNITY_TYPE value="1" callPath="" callJS="" />
        </OVERSEAS>
        <!--副本开关-->
        <DUNGEON_OPENLIST value="0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,70001,12016,15001,16001" advancedEnable="true"
            epicLevelEnable="true" footballEnable="true" />
        <SHOPITEM_SUIT_TOSHOW enable="true" />
        <SUIT enable="true" />
        <!--弹王盟约开关-->
        <KINGBLESS enable="true" />
        <!--任务托管 -->
        <QUEST_TRUSTEESHIP enable="false" />
        <!--勇士秘境开关 -->
        <WARRIORS_FAM enable="true" />
        <!--战魂开关-->
        <GEMSTONE enable="true" />
        <!--337VIP按钮为空不显示跳转按钮-->
        <GOTO337 value="http://web.337.com/pt/activity/vipprize?id=19" />
        <ONEKEYDONE enable="true" />
        <!--邂逅开关-->
        <ENCOUNTER enable="false" />
        <!--农场探宝开关-->
        <TREASURE enable="false" time="5" />
        <!--活力值开关-->
        <ENERGY_ENABLE enable="fales" />
        <!--进阶开关-->
        <EXALTBTN enable="true" />
        <!--赛亚之神 临时属性-->
        <GODSYAH enable="true" />
        <PK_BTN enable="true" />
        <FIGHT_TIME count="2" />
        <PETS_EAT enable="true" />
        <MAGICHOUSE enable="true" />
        <GIRLHEAD enable="true" value="http://179.131.20.57" />
        <GIRDATTEST enable="false" />
        <MAGICBOXBTN enable="true" />
        <BAGINFOGODTEMPLE enable="false" />
        <!--跨区大喇叭-->
        <CROSSBUGGlLEBTN enable="true" />
        <CROSSBUGGLE enable="true" />
        <!--老玩家转区 true:开 false:关-->
        <OLDPLAYER_TRANSFER_SERVER enable="true" />
    </config>
    <update>
        <version from="462" to="463">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/bones.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/bones.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="463" to="464">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/bones.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/bones.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="464" to="465">
            <file value="*" />
            <file value="flash/*" />
        </version>
        <version from="465" to="466">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="466" to="467">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="467" to="468">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="468" to="469">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="469" to="470">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="470" to="471">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="471" to="472">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="472" to="473">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="473" to="474">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="474" to="475">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="475" to="476">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="476" to="477">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="477" to="478">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="478" to="479">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="479" to="480">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="480" to="481">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="481" to="482">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="482" to="483">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="483" to="484">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="484" to="485">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="485" to="486">
            <file value="flash/ui/portugal/starling/hall_scene/hall_newyear_scene_build.xml" />
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/5.png" />
            <file value="flash/ui/portugal/language.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/starling/hall_scene/hall_newyear_scene_build.png" />
            <file value="flash/Loading.swf" />
            <file value="flash/ui/portugal/swf/ddtcoreii.swf" />
            <file value="flash/ui/portugal/swf/ddthallIcon.swf" />
            <file value="flash/ui/portugal/swf/farm.swf" />
            <file value="flash/ui/portugal/swf/firstcore.swf" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="flash/ui/portugal/swf/labyrinth.swf" />
            <file value="ui/portugal/starling/hall_scene/hall_newyear_scene_build.xml" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="5.png" />
            <file value="ui/portugal/language.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/starling/hall_scene/hall_newyear_scene_build.png" />
            <file value="Loading.swf" />
            <file value="ui/portugal/swf/ddtcoreii.swf" />
            <file value="ui/portugal/swf/ddthallIcon.swf" />
            <file value="ui/portugal/swf/farm.swf" />
            <file value="ui/portugal/swf/firstcore.swf" />
            <file value="ui/portugal/swf/horse.swf" />
            <file value="ui/portugal/swf/labyrinth.swf" />
        </version>
        <version from="486" to="487">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/bones.png" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/bones.png" />
        </version>
        <version from="487" to="488">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/bones.png" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/bones.png" />
        </version>
        <version from="488" to="489">
            <file value="flash/5.png" />
            <file value="flash/Loading.swf" />
            <file value="5.png" />
            <file value="Loading.swf" />
        </version>
        <version from="489" to="490">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="490" to="491">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="491" to="492">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="492" to="493">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
        <version from="493" to="494">
            <file value="flash/2.png" />
            <file value="flash/4.png" />
            <file value="flash/ui/portugal/xml.png" />
            <file value="flash/ui/portugal/swf/horse.swf" />
            <file value="2.png" />
            <file value="4.png" />
            <file value="ui/portugal/xml.png" />
            <file value="ui/portugal/swf/horse.swf" />
        </version>
    </update>
</root>
