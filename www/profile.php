<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// profile.php
// $Id: profile.php,v 1.3 2004/05/31 18:29:53 czz Exp $

include ("config.php");

if (!is_login()) {
    header("Location: loginout.php");
    exit();
}

$varibles = array('submit', 'email', 'host', 'mx1', 'mx2', 'ns1', 'ns2', 'bbsid', 'bbsname', 'bbsport', 'bbsdept', 'bbsonline', 'bbslogin', 'password', 'password2', 'xmode', 'omail');

if (!$bbsport) $bbsport = 23;

foreach ($varibles as $tmp) {
    ${$tmp} = &cgi_var($tmp);
}

$name = &my_session_get('dns_name');
$pass = &my_session_get('dns_pass');

if (!isset($submit)) 
    $msg = "请认真填写以下各项！";
elseif (!is_host($host))
    $msg = "BBS地址不合规定？";
elseif (!is_email($email))
    $msg = "电子信箱不合规定？";
elseif (!empty($ns1) && !is_host($ns1))
    $msg = "主域名服务器的输入有误！";
elseif (!empty($ns2) && !is_host($ns2))
    $msg = "辅域名服务器的输入有误！";
elseif (!empty($mx1) && !is_dn($mx1))
    $msg = "Mail eXchange 1的输入有误！";
elseif (!empty($mx2) && !is_dn($mx2))
    $msg = "Mail eXchange 2的输入有误！";
elseif (empty($bbsid) || empty($bbsname))
    $msg = "您在您的BBS上的ID和BBS的中文站名不能为空！";
elseif ($password != $password2)
    $msg = "两次输入的密码不一样。";
else
    $msg = "";

require ("db_mysql.php");
// Create Mysql Class
//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();

if ($msg == "") {
    $introduce = &cgi_var('introduce');

    if (!empty($password)) $newpass = md5($password);
    else $newpass = $pass;

    $wildcard = &cgi_var('wildcard');

    if ($wildcard == 1)
	$xmode |= _HAVE_WILDCARD_;
    else
	$xmode &= ~_HAVE_WILDCARD_;

    if ($mx1 == "") $mx1 = $name . "." . $syscfg['dn'];
    if ($mx2 == $mx1) $mx2 = "";
    if ($ns2 == $ns1) $ns2 = "";

    if ($omail != $email) {
	$xmode &= ~_ACCT_ACTIVE_;
	$authcode = crypt('cn.bbs.*');
	if (strlen($authcode) > 20) $authcode = substr($authcode, 0, 20);
    }
    $now = time();

    $db->query("UPDATE _my_dns SET pass = '$newpass', host = '$host', mx1 = '$mx1', mx2 = '$mx2', ns1 = '$ns1', ns2 = '$ns2', xmode = '$xmode', bbsname = '$bbsname', bbsport = '$bbsport', bbsdept = '$bbsdept', bbsonline = '$bbsonline', bbslogin = '$bbslogin', bbsid = '$bbsid', email = '$email', authcode = '$authcode', authtime = '$now', introduce = '$introduce' WHERE name = '$name' AND pass = '$pass' AND email = '$omail'");


   if($db->affected_rows() == 1) {
       $pass = $newpass; // update session
       $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>修改成功！</b></font><br><br>\n"
       . "<a href=\"list.php\">返回首页</a>\n</p>";
       if ($omail != $email) {
       $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    由于您更改了注册时的电子信箱，为确保您的电子信箱能够正常使用， 您还需要
激活您的帐号才能继续享有本站的各种服务。请您在 24 小时内登录我们的网页
$syscfg[url]/active.php 激活您的帐号。
    修改时间: $now
    激活认证码: $authcode

    您也可以直接点击下面链接来激活您的帐号:
$syscfg[url]/active.php?name=$name&authcode=$authcode
--
$syscfg[url]	 	   
__EOF__;

       @mail($email, "[cn-bbs] 您的激活认证码", $mailmsg, $mhdr);
       	my_session_unset('dns_name');
	my_session_unset('dns_pass');
	$db->close();
	header("Location: loginout.php");
	exit();
       }
   }
   else
      $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>修改失败或者没有修改！请尽快联系本站管理人员解决！</b></font></p>";
}
else {

    if (!isset($omail)) {
	$db->query_first("SELECT host, mx1, mx2, ns1, ns2, xmode, bbsname, bbsport, bbsdept, bbsonline, bbslogin, bbsid, email, introduce FROM _my_dns WHERE pass = '$pass' AND name = '$name'");
	
	$host = $db->f('host');
	$mx1 = $db->f('mx1');
	$mx2 = $db->f('mx2');
	$ns1 = $db->f('ns1');
	$ns2 = $db->f('ns2');
	$xmode = $db->f('xmode');
	$bbsname = $db->f('bbsname');
	$bbsport = $db->f('bbsport');
	$bbsdept = $db->f('bbsdept');
	$bbsonline = $db->f('bbsonline');
	$bbslogin = $db->f('bbslogin');
	$bbsid = $db->f('bbsid');
	$email = $db->f('email');
	$introduce = $db->f('introduce');
    }

    if ($xmode & _HAVE_WILDCARD_) $checked = " checked";
    else $checked = "";

    $introduce = stripslashes($introduce);
    $bbsname = stripslashes($bbsname);
    $bbsdept = stripslashes($bbsdept);
    $bbsonline = stripslashes($bbsonline);
    $bbslogin = stripslashes($bbslogin);

    $string = <<<__EOF__
     <table border="0" width="90%" align="center" bgcolor="#efefef">
     <tr><td colspan="2" align="center"><font color="red"><b>注意: $msg</b></font></td></tr>
     <form action="$PHP_SELF" method="post">
     <tr>
     <td nowrap align="right"><b>您的域名: </b></td>
     <td>
     <input type="text" size="14" maxlength="12" name="name" value="$name" disabled><b>.$syscfg[dn]</b> (如需修改请联系本站管理员)
     </td>
     </tr>
     <tr><td nowrap align="right"><b>更改密码: </b></td>
     <td>
	   <input type="password" name="password" size="14" maxlength="12"> (不改密码请留空！)
     </td>
     </tr>
     <tr><td nowrap align="right"><b>再次确认密码: </b></td>
     <td>
	   <input type="password" name="password2" size="14" maxlength="12"> (不改密码请留空！)
     </td>
     </tr>
     <tr>
     <td nowrap align="right"><b>BBS地址: </b></td>
     <td><input type="text" size="40" maxlength="32" name="host" value="$host">
     </td>
     </tr>
     <tr><td></td><td>
     <ol>
       <li><font color="red">请输入 BBS 的 IP 地址，据此提供 A 记录。如: 166.111.8.238</font></li>
       <li>如果 BBS 不具有静态 IP ，请输入动态域名，据此提供 CNAME 记录。如: dot66.hn.org</li>
       <li>动态域名申请请参考 <a href="http://www.oth.net/dyndns.html" target="_blank">www.oth.net</a> 或
       <a href="http://directory.google.com/Top/Computers/Software/Internet/Servers/Address_Management/Dynamic_DNS_Services/" target="_blank">Google</a>。
       </li>
     </ol>
     </td></tr>
     <tr><td nowrap align="right"><b>您的电子邮件: </b></td>
     <td><input type="text" size="40" maxlength="60" name="email" value="$email"></td>
     </tr>
     <input type="hidden" name="omail" value="$email">
     <tr><td></td><td>
	   更改此信箱需要再次发送激活认证码，在您的帐号再次激活前您将被取消所有权限。所以除非站务人员更迭或者原信箱失效，请勿轻易更改此信箱。(友情提醒：请勿使用 126.com 的信箱注册。)
     </td></tr>
     <tr><td nowrap align="right"><b>主域名服务器: </b></td>
     <td><input type="text" size="40" maxlength="60" name="ns1" value="$ns1"> (即 NS1)</td>
     </tr>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于提供 NS 记录，如果您不明白含义，请勿填写。</li>
       <li>如果您已注册域名服务器，请填写它的域名，如: ns1.smth.org</li>
       <li>域名服务器是否已注册，请通过 <a href="http://www.internic.net/whois.html" target="_blank">Internic</a> 检查。</li>
       <li>如果您没有已注册域名服务器，请输入 IP 地址，据此提供主域名服务器的 A 记录。如: 166.111.8.238
     </ol>
     </td></tr>
     <tr><td nowrap align="right"><b>辅域名服务器: </b></td>
     <td><input type="text" size="40" maxlength="60" name="ns2" value="$ns2"> (即 NS2)</td>
     </tr>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于提供 NS 记录，如果您不明白含义，请勿填写。</li>
       <li><font color="red">如果您输入的内容与上栏相同，请勿填写。</font></li>
       <li>如果您已注册域名服务器，请填写它的域名，如: ns1.smth.org</li>
       <li>域名服务器是否已注册，请通过 <a href="http://www.internic.net/whois.html" target="_blank">Internic</a> 检查。</li>
       <li>如果您没有已注册域名服务器，请输入 IP 地址，据此提供辅域名服务器的 A 记录。如: 166.111.8.237
     </ol>
     </td></tr>
     <tr><td nowrap align="right"><b>Mail eXchange 1: </b></td>
     <td><input type="text" size="40" maxlength="60" name="mx1" value="$mx1"> (MX1，如果您不明白此项含义，请勿更改。)</td>
     </tr>
     <tr><td nowrap align="right"><b>Mail eXchange 2: </b></td>
     <td><input type="text" size="40" maxlength="60" name="mx2" value="$mx2"> (MX2，如果您不明白此项含义，请勿更改。)</td>
     </tr>
     <input type="hidden" name="xmode" value="$xmode">
     <tr><td nowrap align="right"><b>Wildcard: </b></td>
     <td><input type="checkbox" name="wildcard" value="1" $checked> (如果您不明白此项含义，请勿打勾) </td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的中文站名: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsname" value="$bbsname"> (空格请用_代替。如: BBS_水木清华站)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的连接端口: </b></td>
     <td><input type="text" size="10" maxlength="8" name="bbsport" value="$bbsport"> (如: 23)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS所属单位: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsdept" value="$bbsdept"> (如: 清华大学)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS在线人数前导词: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsonline" value="$bbsonline"></td>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于根据进站画面统计在线人数。</li>
       <li><font color="red">如果您使用 Firebird 且未修改进站部分，本栏默认内容为“目前上站人数”。</font></li>
      <li>如果您的BBS在线人数前导词与默认值不同，请根据实际情况填写。如: “上线人数”、“当前在线”、“目前站内石头”、“目前轩上游客”、“目前在城内闲逛的石头”等。</li>
     </ol>
     </td></tr>
     </tr>
     <tr><td nowrap align="right"><b>BBS登录进站前导词: </b></td>
     <td><input type="text" size="10" maxlength="8" name="bbslogin" value="$bbslogin"></td>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于根据进站画面统计在线人数。</li>
       <li><font color="red">目前国内绝大多数的 BBS 已不需要在登录时输入 bbs ，故此栏可以不填写。</font></li>
      <li>如果您的BBS登录进站前导词与默认值不同，请根据实际情况填写。如: bbs</li>
     </ol>
     </td></tr>
     </tr>
     <tr><td nowrap align="right"><b>您在您的BBS上的ID: </b></td>
     <td><input type="text" size="20" maxlength="16" name="bbsid" value="$bbsid"> (请输入最常用的ID，如: hightman)</td>
     </tr>
     <tr><td nowrap align="right" valign="top"><b>您的BBS的内容及特色: </b></td>
     <td><textarea name="introduce" cols="60" rows="10">$introduce</textarea></td>
     </tr>
     <tr><td align="center" colspan="2">
     <input type="submit" name="submit" value=" 确 定 ">
     <input type="reset" value=" 重 写 "> 
     </td></tr>
     </form>
     </table>
__EOF__;
}

$db->close();
$action = "profile";
include ("header.php");
print $string;
include ("footer.php");
exit();
?>
