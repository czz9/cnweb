<?php
//头部文件
//author: hightman@hightman.net
//$Id: header.php,v 1.2 2003/03/23 17:00:47 czz Exp $


$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

if (!isset($action)) $action = "list";
$lists = Array(
		    "list" => "BBS 列表",
		    "listnews" => "服务器列表",
		    "listgroup" => "新闻组列表",
		    "help" => "F.A.Q.",
		    "admin" => "系统管理"
		);

if (is_login()) {
    $columns['loginout'] = "退出本站";
    $columns['profile'] = "修改资料";
    $columns['joininn'] = "转信申请";
    $columns['innconf'] = "转信配置提示";
}
else {
    $columns['loginout'] = "登录进站";
    $columns['register'] = "域名申请";
    $columns['active'] = "激活帐号";
    $columns['lostpw'] = "取回密码";
}

if (! (isset($lists[$action]) || isset($columns[$action]))) die("Access denied!");

foreach ($columns as $key => $value)
    if ($key != "admin") {
		if($key == $action)
	        $headmsg .= "\t\t<li><b>[<a href=\"" . $key . ".php\">" . $value . "</a>]</b></li>\n";
		else
	        $headmsg .= "\t\t<li><a href=\"" . $key . ".php\">" . $value . "</a></li>\n";
    }

header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");    // okay, it will not be expired for ever.
header("Date: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check=1, pre-check=1");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $syscfg['title']?> - <?php echo $columns[$action]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="default.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF">
<div id="divLogo"><a href="index.php"><img src="logo.gif" width="369" height="50" border="0" /></a></div>
<div id="divMenu">
	<br />
	<div class="menutitle">新闻组数据</div>
	<ul>
		<li><a href="listnews.php">服务器列表</a></li>
		<li><a href="listgroup.php">新闻组列表</a></li>
		<li><a href="http://www.cn-bbs.org/index.php?c">转信介绍与文档</a></li>
	</ul>
	<div class="menutitle">转信申请</div>
<?php
	print("\t<ul>\n" . $headmsg . "\t</ul>");
?>
</div>
<div id="divMain"><div style="margin:20px">