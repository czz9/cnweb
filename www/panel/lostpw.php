<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// lostpw.php
// $Id$

include ("config.php");
$name = &cgi_var('name');

if (isset($name)) {
    require("db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
	$db->connect();
    $db->query_first("SELECT email FROM _my_dns WHERE name = '$name' LIMIT 1");
    $email = $db->f('email');

    if (!isset($email))
	$string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>错误的帐号名称！</b></font><br><br>\n"
	. "请点击<a href=\"{$_SERVER['PHP_SELF']}\">这里</a>重新输入您的帐号！ </p>";
    else {
	$newpass = crypt(substr(time(), 4));
	$newpass2 = md5($newpass);
	$db->query("UPDATE _my_dns SET pass = '$newpass2' WHERE NAME = '$name'");
	if($db->affected_rows() == 1) {
	    $mhdr = "From: " . $syscfg['email'] . "\r\nReply-To: " . $syscfg['email'] . "\r\nX-Mailer: php program by hightman.";
	    $now = date("Y-m-d H:i:s");
	    $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    您已重置了您的登录密码。
    修改时间：($now)
    新密码：$newpass

    请您立即登录我们的网页 $syscfg[url]/loginout.php ， 修改您的密码，并注意保管好！
--
$syscfg[url]	 	   
__EOF__;
	    @mail($email, "[cn-bbs] 您的新密码", $mailmsg, $mhdr);

	    $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>取回成功！</b></font><br><br>\n"
	    . "您的新密码已经发送到注册信箱，请尽快查看您的信箱！</p>";
	}
	else
	    $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>取回失败！</b></font><br><br>\n"
	    . "请尽快联系本站管理员解决！</p>";
    }
    
    $db->close();
}
else {
    $string = <<<__EOF__
	<div algin="center">	
	<table align="center" border="0" bgcolor="#fefefe">
	<form action="$PHP_SELF" method="post">
	<tr><th colspan="2" align="left">请在下面输入您的帐号名称: </th></tr>
	<tr><td><b>登录名称: </b></td>
	<td>
	<input type="text" name="name" size="20" maxlength="16">
	<input type="submit" value="确定">
	</td></tr>
	<tr><td colspan="2" align="center">
	<a href="register.php">尚未注册？</a>	
	</td></tr>
	</table>
	</div>
__EOF__;
}

$action = "lostpw";
include("header.php");
print $string;
include("footer.php");
exit();

?>
