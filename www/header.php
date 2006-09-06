<?php
//头部文件
//author: hightman@hightman.net
//$Id: header.php,v 1.2 2003/03/23 17:00:47 czz Exp $


$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

if (!isset($action)) $action = "list";
$columns = Array(
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

if (!isset($columns[$action])) die("Access denied!");

$headmsg = "| ";

foreach ($columns as $key => $value)
    if ($key != "admin") {
        $headmsg .= "<a href=\"" . $key . ".php\">" . $value . "</a> |\n";
    }

header("Expires: Mon, 26 Jul 2000 05:00:00 GMT");    // okay, it will not be expired for ever.
header("Date: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: post-check=1, pre-check=1");

?>

<html>
<head>
<title><?php echo $syscfg['title']?> - <?php echo $columns[$action]?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
<!--
body, td, th { font-size: 12px; font-family: sans-serif; }
a { font-family: sans-serif; font-size: 12px; color: #0000f0; text-decoration: none } 
a:hover { color : #f00000; text-decoration : underline } 
form { font-family: sans-serif; }
select { font-size: 12px }
input, object { background-color: #ffffff; font-size: 12px; border: 1px #cccccc double; font-family: sans-serif; }
option { background-color: #f3f3f3; color: #51485f }
textarea { background-color: #ffffff; font-size: 12px ; border: 1px #cccccc double; overflow: auto; clip:rect() }
//-->
</style>
</head>
<body bgcolor="#FFFFFF">
<h1 align="center"><b><?php echo $columns[$action]?></b></h1>
<p align="center"><?php echo $headmsg?></p>
<table border="0" width="90%" align="center">
<tr><td width="100%" wrap>
