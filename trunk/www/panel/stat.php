<?php

	include("config.php");
	include("db_mysql.php");
	
	$db = new db_mysql($syscfg['mysql']);
	$db->connect();
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_srv");
	$srv_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _news_grp WHERE type!=2");
	$grp_count = $db->f("count");
	$db->query_first("SELECT COUNT(*) 'count' FROM _my_dns WHERE (xmode&" . _HOST_ACTIVE_ . ")");
	$bbs_count = $db->f("count");
	$db->close();
	
	$action = "stat";
	include("header.php");

	print <<<___EOF___
<p>欢迎使用 cn-bbs.org 转信申请控制面板，请从左边点击“登录进站”进入转信查询和申请页面，新用户请选择“域名申请”进行注册。</p>
<p>目前 cn.bbs.* 新闻组共有服务器 {$srv_count} 台，有 {$grp_count} 个可供转信的新闻组，现有 {$bbs_count} 个 BBS 站点参与转信。</p>
___EOF___;
	include("footer.php");
?>