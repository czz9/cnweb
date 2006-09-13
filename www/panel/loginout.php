<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// loginout.php
// $Id$

include ("config.php");

$name = &cgi_var('name');
$password = &cgi_var('password');
$showform = 1;
$errmsg = "";

if (is_login()) { // logout.
    my_session_unset('dns_name');
    my_session_unset('dns_pass');
    header("Location: index.php");
    exit();
}
if (isset($name) && isset($password)) {
    require("db_mysql.php");
    $password = md5($password);
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();
    $db->query_first("SELECT xmode FROM _my_dns WHERE pass = '$password' AND name = '$name' LIMIT 1");
    $xmode = $db->f('xmode');
    $db->close();

    if (!isset($xmode))
	$errmsg = "<p algin=\"center\"><font color=\"red\" size=\"3\">登录出错，请仔细回忆您的密码重新登录！</font></p>\n";
    elseif ($xmode & _ACCT_ACTIVE_) {
		my_session_set('dns_name', $name);
		my_session_set('dns_pass', $password);
		$db->query("UPDATE _my_dns SET lastip=thisip WHERE name='$name'");
		$db->query("UPDATE _my_dns SET thisip='" . $_SERVER['REMOTE_ADDR'] . "' WHERE name='$name'");
		header("Location: query.php?f");
		exit();
    }
    else {
	$showform = 0;
	$string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>帐号尚未激活！</b></font><br><br>\n"
	. "请查看邮箱后，点击<a href=\"active.php\">这里</a>激活！ </p>";
    }
}

if ($showform) {
    $string = <<<__EOF__
	<div algin="center">	
	<table align="center" border="0" bgcolor="#fefefe">
	<form action="$PHP_SELF" method="post">
	<tr><td colspan="2" align="center"> $errmsg </td></tr>
	<tr><th colspan="2" align="left">请在下面输入您的帐号名称和密码来登录: </th></tr>
	<tr><td><b>登录名称: </b></td>
	<td><input type="text" name="name" size="20" maxlength="16"></td></tr>
	<tr><td><b>登录密码: </b></td>
	<td><input type="password" name="password" size="20" maxlength="40">
	<input type="submit" value="确定">
	</td>
	</tr>
	<tr><td colspan="2" align="center">
	<a href="register.php">尚未注册？</a>
	<a href="lostpw.php">忘记密码？</a>	
	</td></tr>
	</table>
	</div>
__EOF__;
}

$action = "loginout";
include("header.php");
print $string;
include("footer.php");
exit();

?>
