
<?xml version="1.0" encoding="UTF-8"?>

<root> 
  <config> 
    <FLASHSITE value="{{ $server->flash }}/"/>  
    <SITE value="{{ $server->resource }}/"/>  
    <!-- md5校验失败后的备用加载地址 -->  
    <BACKUP_FLASHSITE value="{{ $server->flash }}/"/>  
    <!-- 是否使用md5头校验 -->  
    <USE_MD5 value="{{ $server->settings->md5 == 1 ? 'true' : 'false' }}"/>  
    <FIRSTPAGE value="{{ url() }}/"/>  
    <REGISTER value="{{ url() }}/"/>  
    <REQUEST_PATH value="{{ $server->quest }}/"/>  
    <LOGIN_PATH value="{{ url() }}/" siteName="DDT"/>  
    <CALL_PATH value=""/>
    <RESOURCE_UPFILE_NAME value="resourceUpFile"/>  
    <USER_ACTION_NOTICE value=""/>  
    <!--51召唤接口-->  
    <CALLBACK_INTERFACE path="http://gameapi.51.com/ddt_zh" enable="false"/>  
    <!-- 多玩接口-->  
    <FILL_PATH value="{{ url() }}/"/>  
    <POLICY_FILES> 
      <file value="{{ $server->flash }}/crossdomain.xml"/>  
      <file value="{{ url() }}/crossdomain.xml"/> 
    </POLICY_FILES>  
    <!-- 作战实验室开关-->  
    <FIGHTLIB value="true"/>  
    <!-- 是否允许多开-->  
    <ALLOW_MULTI value="true"/>  
    <!-- 新手的，已弃用-->  
    <TRAINER_PATH value="tutorial.swf"/>  
    <MUSIC_LIST value="1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1023,1024,1025,1026,1027,1028,1029,1030,1031,1032,1034,1035,1036,1037,1038,1039,1040,1059,1060,1061,1062,1063,1065,1067,1068,1069,1077"/>  
    <LANGUAGE value="{{ $server->settings->lang }}"/>  
    <!-- 代理商编号-->  
    <PARTER_ID value="1"/>  
    <!-- 进入游戏 和 进入战斗的统计开关 -->  
    <STATISTIC value="true"/>  
    <!-- 游戏里面的退出时间限制，单位秒 -->  
    <SUCIDE_TIME value="120"/>  
    <!-- 战斗中的箱子显示，1箱子，3粽子，4月饼，5南瓜，6铃铛，7灯笼,8新春灯笼-->  
    <GAME_BOXPIC value="3"/>  
    <ISTOPDERIICT value="true"/>  
    <!-- 统计数据,如新手帮助,本地保存 -->  
    <COUNT_PATH value="http://assayerhandler.7road.com/"/>  
    <!-- 社区开关 已弃用 -->  
    <PHP isShow="false" link="false" site="http://baidu.com/" infoPath="http://stest.ddt.kaixin001.com.cn/test/SnsServer/GetSnsUserPortrait?uid={uid}"/>  
    <!-- 官网地址 已弃用 -->  
    <OFFICIAL_SITE value="http://www.7road.com"/>  
    <!--游戏论坛地址 已弃用 -->  
    <GAME_FORUM value="http://hi.baidu.com"/>  
    <!-- 九城社区开关-->  
    <COMMUNITY_FRIEND_PATH isUser="false" value="http://d9.the9.com/webgame/ddt/addfriend.php"/>  
    <COMMUNITY_INVITE_PATH value="http://d9.the9.com/webgame/ddt/invite.php"/>  
    <!-- 社区开关-->  
    <COMMUNITY_FRIEND_LIST_PATH value="http://test91.ddt.7road-inc.com:728/IMFriendsBbs.ashx" snsPath="http://stest.ddt.kaixin001.com.cn/test/SnsServer/NotifySnsUserUpgrade" isexist="false" isexistBtnVisble="false"/>  
    <COMMUNITY_FRIEND_INVITED_SWITCH value="false" invitedOnline="false"/>  
    <!-- 新浪微博第1代，把社区显示为微博-->  
    <COMMUNITY_MICROBLOG value="false"/>  
    <!-- 新浪微博第2代-->  
    <COMMUNITY_SINA_SECOND_MICROBLOG value="false"/>  
    <!-- s是否允许弹出加入收藏夹 -->  
    <ALLOW_POPUP_FAVORITE value="true"/>  
    <!-- 盛大圈圈 -->  
    <!--是否关闭喇叭 已弃用 -->  
    <SHIELD_NOTICE value="false"/>  
    <!--单机版新手引导的开关-->  
    <TRAINER_STANDALONE value="true"/>  
    <!--3.3之前的新版新手引导开关-->  
    <USER_GUILD_ENABLE value="true"/>  
    <!--远征码头等级限制-->  
    <MINLEVELDUPLICATE value="8"/>  
    <!-- DISABLE_TASK_ID 为禁用的任务的ID，如有多个，请用逗号隔开 for example : 1002,1003,1004,新浪需要屏蔽的任务有378,379,380,381,604 -->  
    <DISABLE_TASK_ID value="382,383,384,385,550"/>  
    <!-- 登陆器下载地址，地址为空就不显示登陆器下载按钮 -->  
    <CLIENT_DOWNLOAD value="http://client.7road.com/updates/7k7k/7K7K弹弹堂极速登录器.exe"/>  
    <!-- 360游戏统计，包括登陆，进入战斗等-->  
    <EXTERNAL_INTERFACE_360 value="http://s.1360.cn/game_event" enable="false"/>  
    <!--小游戏等级限制开关-->  
    <LITTLEGAMEMINLV value="20"/>  
    <!-- 游戏边框背景开关 -->  
    <SHOW_BACKGROUND value="true"/>  
    <!-- 交友中心和结婚的开关-->  
    <MODULE> 
      <CIVIL enable="true"/>  
      <CHURCH enable="true"/> 
    </MODULE>  
    <!-- 客服系统和邮件举报的开关,添加telNumber属性,如果为空则不显示客服电话-->  
    <FEEDBACK enable="true" telNumber="0755-71231231"/>  
    <!-- 是否显示猪表情-->  
    <CHAT_FACE> 
      <DISABLED_LIST list="38"/> 
    </CHAT_FACE>  
    <!-- 武器粒子在多少帧数以下就不显示，已弃用-->  
    <GAME_FRAME_CONFIG> 
      <!-- 每帧执行的毫秒数,正常值应为40毫秒,并允许在35至45毫秒之间波动  (游戏内正常帧速为 25帧/秒)-->  
      <FRAME_TIME_OVER_TAG value="67"/>  
      <!-- 连续低于上述毫秒的帧数 (游戏内正常帧速为 25帧/秒) -->  
      <FRAME_OVER_COUNT_TAG value="25"/> 
    </GAME_FRAME_CONFIG>  
    <EXTERNAL_INTERFACE path="http://fb.egame.hk/GameAccess/facebook/ddt/postAction.jsp" enable="false"/>  
    <!-- 是否显示官网登录器每日领取图标 -->  
    <LANDERS_AWARD_OFFICIAL value="true"/>  
    <!--     4399的二级密码开关 -->  
    <!-- 二级密码开关value的值:  -1没有手机找回的，0为有手机找回的（默认），1为4399的手机找回，-1为多玩没有手机找回-->  
    <LOCK_SETTING value="0"/>  
    <!-- 多玩YY开通界面显示 -->  
    <DUO_WAN_YY_VIP_SHOW_OPEN_VIEW value="0"/>  
    <!-- 是否显示多玩YY VIP -->  
    <DUO_WAN_YY_VIP value="0"/>  
    <!-- 多玩开通/续费VIP链接 -->  
    <DUO_WAN_YY_VIP_OPEN_URL value=""/>  
    <!-- 是否显示4399登录器每日领取图标 -->  
    <LANDERS_AWARD_4399 value="true"/>  
    <!--至尊弹王争霸赛防外挂开关，true开启防外挂 -->  
    <BOMBKing_KILL_CHEAT value="true"/>  
    <BEAUTY_PROVE_QQ value="2629638768"/> 
    <!--resourceUpFile版本缓存控制文件站点 -->
    <RESOURCE_UPFILE value=""/>
    <!--是否启动版本缓存-->
    <RESOURCE_UPFILE_ENABLE value="true"/>
    <!--所有flash目录下资源加载使用这个地址，resource站点地址 + /flash/ -->
    <RESOURCE_SITE value="{{ $server->flash }}/"/>
  </config>  
</root>
