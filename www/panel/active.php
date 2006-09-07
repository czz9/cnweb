<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// active.php
// $Id: active.php,v 1.2 2003/03/23 17:00:46 czz Exp $

include ("config.php");

$name = &cgi_var('name');
$authcode = &cgi_var('authcode');


if (isset($name) && isset($authcode)) {
    require("db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);
    $db = new db_mysql($syscfg['mysql']);
    $db->connect();
    $xmode = _ACCT_ACTIVE_;
    $db->query("UPDATE _my_dns SET xmode = (xmode | $xmode) WHERE authcode = '$authcode' AND name = '$name' AND !(xmode & $xmode)");
   if($db->affected_rows() == 1)
	$string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>帐号激活成功！</b></font><br><br>\n"
	. "如果您是首次注册且您的 BBS 不对管理员所在网域开放，请尽快来信说明<br>\n"
	. "请点击<a href=\"loginout.php\">这里</a>登录！ </p>";
   else {
	$string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>激活失败！<b></font><br><br>\n"
	. "请点击<a href=\"{$_SERVER['PHP_SELF']}\">这里</a>重试！</b></font></p>\n";
   }       
    $db->close();
}
else {
    $string = <<<__EOF__
	<div algin="center">	
	<table align="center" border="0" bgcolor="#fefefe">
	<form action="$PHP_SELF" method="post">
	<tr><th colspan="2" align="left">请在下面输入您的帐号名称和激活认证码: </th></tr>
	<tr><td><b>登录名称: </b></td>
	<td><input type="text" name="name" size="20" maxlength="16"></td></tr>
	<tr><td><b>激活认证码: </b></td>
	<td><input type="text" name="authcode" size="20" maxlength="40"></td>
	</tr>
	<tr><td colspan="2" align="center">
	<input type="submit" value="确定">
	<input type="reset" value="重写">
	</td></tr>
	</table>
	</div>
__EOF__;
}

$action = "active";

include("header.php");
print $string;
include("footer.php");
exit();

?>
