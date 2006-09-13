<?php
// bbs list
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id$

include("config.php");
$id = &cgi_var('id');

if (!$id) die ("Empty Input!");

if(isset($_GET["f"]))
	$f = true;
else
	$f = false;

require("db_mysql.php");
//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();

$db->query("SELECT id, name FROM _news_grp");
$groups = array();
while($db->next_record()) {
    $tmp_id = $db->f('id');
    $tmp_name = $db->f('name');
    $groups[$tmp_id] = $tmp_name;
}
$db->close();

$tmp = $db->query_first("SELECT name, host, xmode, bbsname, bbsport, bbsdept, innsrv, bbsid, email, groups, introduce FROM _my_dns WHERE id = '$id' OR name = '$id' LIMIT 1");
$db->close();
$innsrv = $db->f('innsrv');

if (!is_array($tmp)) die ("Error Input: [" . $id . "]");

$status = ($tmp['xmode'] & _ACCT_ACTIVE_ ? "<font color=\"green\">已激活</font> " : "<font color=\"red\">未激活</font> ");
$status .= ($tmp['xmode'] & _HOST_ACTIVE_ ? "<font color=\"green\">已通过审核</font> " : "<font color=\"red\">未通过审核</font> ");
if (!$innsrv)
	$status .= ("<font color=\"red\">未加入转信</font>");
elseif ($innsrv == -1)
	$status .= ("<font color=\"blue\">测试成员申请中</font>");
elseif ($innsrv == -2)
	$status .= ("<font color=\"green\">已成为测试成员</font> <font color=\"blue\">正式成员申请中</font>");
elseif ($tmp['xmode'] & (_INN_PASSIVE_)) {
	$server = $db->query_first("SELECT name, url FROM _news_srv WHERE id = '$innsrv' LIMIT 1");
	$db->close();
	$status .= ("<font color=\"green\">已成为正式成员</font> (上游服务器: <a href=\"listnews.php#" . $innsrv . "\" target=\"_blank\">" . $server['name'] . "</a>)");
}
elseif ($tmp['xmode'] & (_INN_ACTIVE_))
	$status .= ("<font color=\"green\">已成为测试成员</font> <font color=\"red\">正式成员待申请</font>");
else
	$status .= ("<font color=\"green\">已成为测试成员</font> <font color=\"red\">转信设置待提交</font> <font color=\"red\">正式成员待申请</font>");

$tmp['groups'] = unserialize($tmp['groups']);
$grouplist = "";
settype($tmp['groups'], "array");
asort($tmp['groups']);
reset($tmp['groups']);
foreach($tmp['groups'] as $key => $value) {
	$grouplist .= ("<font color=\"green\">" . $value . "</font>\t\t<font color=\"blue\"><a href=\"listgroup.php#" . $groups[$key] . "\" target=\"_blank\">". $groups[$key] . "</a></font>\n");
}
if($f) {
	include("header.php");
	print("<h3>您的账号信息： $tmp[name]</h3>\n");
}
else {
	print <<<__EOF__
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title> User Query: $tmp[name] </title>
</head>
<body>
<h3>Query: $tmp[name]</h3>
__EOF__;
}
print <<<__EOF__
<pre style="font-size: 12px">
登录名称: <b>$tmp[name][.$syscfg[dn]]</b>
BBS中文名称: $tmp[bbsname]
BBS所属单位: $tmp[bbsdept]
BBS地址: $tmp[host]
BBS端口: $tmp[bbsport]
账号状态: $status
申请人: $tmp[bbsid] 
电子信件: $tmp[email]
BBS简介: 
$tmp[introduce]
转信版与新闻组对应关系:
$grouplist
</pre>
__EOF__;
if($f)
	include("footer.php");
else {
	print <<<__EOF__
</body>
</html>
__EOF__;
}

?>
