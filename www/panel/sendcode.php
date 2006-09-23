<?php
include("config.php");
include("db_mysql.php");
include("funcs.php");

if(isset($_POST["name"])) {
	$name = $_POST["name"];
	$password = md5($_POST["password"]);
	$email = $_POST["email"];
	if(!is_email($email)) {
		$string = "<div align=\"center\" style=\"color:#ff0000\">电子邮件地址不正确，请点击<a href=\"sendcode.php\">这里</a>重新填写。</div>";
	}
	else {
		$db = new db_mysql($syscfg['mysql']);
		$db->connect;
	$authcode = crypt('cn-bbs.org'); //rand auth code.
	if (strlen($authcode) > 20) $authcode = substr($authcode, 0, 20);
	$db->query("UPDATE _my_dns SET email='$email',authcode='$authcode' WHERE name='$name' AND pass='$password'");
	if($db->affected_rows() == 1) {
		$mhdr = "From: " . $syscfg['email'] . "\r\nReply-To: " . $syscfg['email'] . "\r\nX-Mailer: php program by hightman.";
		$now = date("Y-m-d H:i:s");       
		$mailmsg = authcode_mail_content($name, $syscfg["url"], $now, $authcode);
		@mail($email, "[cn-bbs] 您的激活认证码", $mailmsg, $mhdr);
		$string = "<div align=\"center\">您的激活码已经重新发送，请收到后点击<a href=\"active.php\">这里</a>激活。</div>";
	}
	else {
			$string = "<div align=\"center\" stlye=\"color:#ff0000\">您输入的登录名和密码不正确，请点击<a href=\"sendcode.php\">这里</a>返回重新输入。</div>";
		}
	}
}
else {
	$string = <<<___EOF___
	<div algin="center">	
	<table align="center" border="0" bgcolor="#fefefe">
	<form action="$PHP_SELF" method="post">
	<tr><th colspan="2" align="left">请在下面输入您的帐号名称和密码，并填写重新接收激活码的Email: </th></tr>
	<tr><td><b>登录名称: </b></td>
	<td><input type="text" name="name" size="20" maxlength="16"></td></tr>
	<tr><td><b>登录密码: </b></td>
	<td><input type="password" name="password" size="20" maxlength="40">
	<tr><td><b>电子邮箱: </b></td>
	<td><input type="text" name="email" size="40" maxlength="60"></td></tr>
	<tr><td align="center" colspan="2">
	<input type="submit" value="确定">
	</td>
	</tr>
	</table>
	</div>
___EOF___;
}

$action = "active";
include("header.php");
print($string);
include("footer.php");
?>