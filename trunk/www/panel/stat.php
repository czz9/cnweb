<?php
	include("config.php");
	include("db_mysql.php");
	
	$db = new db_mysql($syscfg['mysql']);
	$db->connect();
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_srv");
	$srv_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_srv WHERE status=1");
	$srv_working_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_grp WHERE type!=2");
	$grp_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_grp WHERE type!=2 AND `name` LIKE '%cn.bbs%'");
	$grp_cnbbs_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _my_dns");
	$bbs_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _my_dns WHERE (xmode&" . _HOST_ACTIVE_ . ")");
	$bbs_active_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _my_dns WHERE (xmode&" . _INN_ACTIVE_ . ")");
	$bbs_inn_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _my_dns WHERE (xmode&" . _INN_PASSIVE_ . ")");
	$bbs_passive_count = $db->f("count");
	$db->close();
	
	$action = "stat";
	include("header.php");

	print <<<___EOF___
<p>欢迎使用 cn.bbs.* 控制面板。请从左边点击“系统登录”进入转信申请和修改页面，新用户请选择“成员注册”进行注册。</p>
<p>系统概况：<br />
目前我们共有服务器 {$srv_count} 台，其中正常工作 {$srv_working_count} 台。<br />
有 {$grp_count} 个可供转信的新闻组，其中包括 {$grp_cnbbs_count} 组 cn.bbs.*。<br />
有 {$bbs_count} 个 BBS 站点在此注册，其中 {$bbs_active_count} 个已激活。<br />
激活用户中，{$bbs_inn_count} 个参与转信，其中正式成员 {$bbs_passive_count} 个。</p>
___EOF___;
	include("footer.php");
?>
