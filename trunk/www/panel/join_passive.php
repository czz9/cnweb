<?php
// join passive innbbs
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id$

include ("config.php");
if (!is_login()) {
    header("Location: loginout.php");
    exit();
}

require ("db_mysql.php");

// Create Mysql Class
//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();

$name = &my_session_get('dns_name');
$pass = &my_session_get('dns_pass');
$haveinn = (_INN_PASSIVE_ | _INN_ACTIVE_); //已转信;

$db->query("UPDATE _my_dns SET innsrv = -2 WHERE name = '$name' AND pass = '$pass' AND innsrv > 0 AND (xmode & $haveinn)");

if($db->affected_rows() == 1)
    $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>申请已经提交！</b></font><br><br>\n"
    . "在您成功安装全国十大热门话题(见 <a href=http://www.cn-bbs.org/index.php?#Documentation_and_FAQs target=_blank>F.A.Q.</a> 之 Q8)之后，管理员将审核您的申请，并将结果发信通知您。</p><br><br>\n"
    . "<center><a href=\"list.php\">按此返回</a></center>\n";
else
    $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>申请失败！</font>\n"
    . "请及时联系本站管理员予以解决\n<a href=\"loginout.php\">重新登录</a></b></p>\n";

$db->close();

$action = "joininn";
include ("header.php");
print $string;
include ("footer.php");
exit();

?>
