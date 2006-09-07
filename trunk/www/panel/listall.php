<?php
// bbs list
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id: listall.php,v 1.1 2003/12/06 12:12:12 czz Exp $

include("config.php");
require("db_mysql.php");

$valid = _HOST_ACTIVE_;
$active = _ACCT_ACTIVE_;

//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();
$db->query("SELECT id, name, host, bbsname, bbsport, bbsdept, innsrv FROM _my_dns WHERE (xmode & $valid) AND (xmode & $active)");

$tmp_str = "<form action=\"query.php\" style=\"margin: 0px\" target=\"_blank\">";

$tmp_str .= "&nbsp;&nbsp;输入用户名: <input type=\"text\" name=\"id\" size=\"16\" maxlength=\"20\"> (如: SMTH)\n"
."<input type=\"submit\" value=\"Query\" style=\"font-size: 10px\"></form>\n";

$string = <<<__EOF__
	<center>$tmp_str</center>
	<div align="center">
	<table border="0" cellspacing="1" cellpadding="6" align="center" width="80%" bgcolor="#fefefe">
	<tr><th>BBS站名</th><th>BBS地址</th><th>BBS端口</th><th>所属单位</th><th>是否转信</th></tr>

__EOF__;

while ($tmp = $db->fetch_array()) {

    $flag = "";
    if($tmp['innsrv'])
	$flag = ("<font color=\"red\">√</font>");
    $string .= <<<__EOF__
	<tr bgcolor="#fafafa" align="center">
	<td><a href="query.php?id=$tmp[id]" target="_blank">$tmp[bbsname]</a></td>
	<td><a href="telnet:$tmp[host]:$tmp[bbsport]">$tmp[name].$syscfg[dn]</a></td>
	<td>$tmp[bbsport]</td>
	<td>$tmp[bbsdept]</td>
	<td>$flag</td>
	</tr>

__EOF__;
}

$string .= "\t</table></div>\n<center>" . $tmp_str . "</center>\n";

$db->close();

$action = "list";
include("header.php");
print $string;
include("footer.php");
?>
