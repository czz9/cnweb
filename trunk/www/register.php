<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id: signup.php,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $

include ("config.php");

$varibles = array('submit', 'name', 'email', 'host', 'mx1', 'mx2', 'ns1', 'ns2', 'bbsid', 'bbsname', 'bbsdept', 'bbsonline', 'bbslogin', 'bbsport', 'password', 'password2', 'auth');

if (!$bbsport) $bbsport = 23;

foreach ($varibles as $tmp) {
    ${$tmp} = &cgi_var($tmp);
}

$num_auth = &my_session_get('num_auth');

if (!isset($submit)) 
    $msg = "请认真填写以下各项！";
elseif (function_exists("imagecreatefromjpeg") && strcasecmp($num_auth, $auth))
    $msg = "数字签证不对，请核实！";
elseif (!is_valid($name))
    $msg = "您申请的域名不合规定！必须由 2-12 个字符组成。";
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
elseif (empty($bbsid) || empty($bbsname) || empty($password))
    $msg = "您在您的BBS上的ID和BBS的中文站名以及登录密码不能为空！";
elseif ($password != $password2)
    $msg = "两次输入的密码不一样。";
else {
    require ("db_mysql.php");

    // Create Mysql Class
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();

    $count = $db->query_first("SELECT COUNT(*) FROM `_keep_dns` WHERE name = '$name'");
    $count = intval($count[0]);
    if ($count == 0) {
	$count = $db->query_first("SELECT COUNT(*) FROM `_my_dns` WHERE name = '$name'");
	$count = $count[0];
    }
    
    if ($count > 0)
	$msg = "对不起，这个名字 <b>" . $name . "</b> 已经有人使用！";
    elseif (in_array($name, $syscfg['badid']))
	$msg = "对不起，这个名字 <b>" . $name . "</b> 因为某些原因不被接受！";
    else
	$msg = "";
}

if ($msg == "") {
    $introduce = &cgi_var('introduce');
    $xmode = 0;

    $password = md5($password);

    $wildcard = &cgi_var('wildcard');

    if ($wildcard == 1)
	$xmode |= _HAVE_WILDCARD_;

    if ($mx1 == "") $mx1 = $name . "." . $syscfg['dn'];
    if ($mx2 == $mx1) $mx2 = "";
    if ($ns2 == $ns1) $ns2 = "";

    $authcode = crypt('cn-bbs.org'); //rand auth code.
    if (strlen($authcode) > 20) $authcode = substr($authcode, 0, 20);

    $db->query("INSERT INTO _my_dns VALUES ('', '$name', '$password', '$host', '$mx1', '$mx2', '$ns1', '$ns2', '$xmode', '$bbsname', '$bbsport', '$bbsdept', '$bbsonline', '$bbslogin', '', '7777', '0', '$bbsid', '$email', '$authcode', '$now', '', '$introduce')");

   if($db->affected_rows() == 1) {
       $mhdr = "From: " . $syscfg['email'] . "\r\nReply-To: " . $syscfg['email'] . "\r\nX-Mailer: php program by hightman.";
       $now = date("Y-m-d H:i:s");       
       $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    为确保您的电子信箱能够正常使用，您需要激活您的帐号才能接受管理员的审核。
请您在 24 小时内登录我们的网页 $syscfg[url]/active.php 激活您的帐号，超过时
间您必须重新注册。
    注册时间: $now
    激活认证码: $authcode

    您也可以直接点击下面链接来激活您的帐号: 
$syscfg[url]/active.php?name=$name&authcode=$authcode
--
$syscfg[url]	 	   
__EOF__;

       @mail($email, "[cn-bbs] 您的激活认证码", $mailmsg, $mhdr);
       $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>申请成功！</b></font><br><br>\n"
       . "您的申请已经提交，<b>请尽快查看您的电子信箱，信中介绍了激活账号的方法！</b>\n"
       . "<br>(激活认证码的有效期为 24 小时，过期未激活的帐号将予以删除。)\n"
       . "<br>管理员审核通过后会再发电子信件通知您，之后便可申请转信。</p>";
   }
   else
       $string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>申请失败！请尽快联系本站管理人员！</b></font></p>";

   $db->close();
}
else {

    if (function_exists("imagecreatefromjpeg")) {
	$num_auth = <<<__EOF__
	    <tr><td align="right"><b>数字签证: </b></td>
	    <td><input type="text" name="auth" size="14" maxlength="10"></td>
	    </tr><tr><td></td>
	    <td>
	    请输入下图中由黑色<b>英文字母(a-z)</b>和<b>数字(1-9)</b>组成的 <b>4</b> 位字符串。<br>	    
	    如果您看不清楚，请刷新后重试。<br>	    
	    <img src="img_rand.php" border="1" align="absmiddle" alt="数字认证图" width="200" height="72">
	    </td>
	    </tr>
__EOF__;
    }

    if (!$bbsport) $bbsport = 23;
    if (empty($bbsonline)) $bbsonline = "目前上站人数";
    $introduce = stripslashes($introduce);
    $bbsname = stripslashes($bbsname);
    $bbsdept = stripslashes($bbsdept);
    $bbsonline = stripslashes($bbsonline);
    $bbslogin = stripslashes($bbslogin);

    $string = <<<__EOF__
     <table border="0" width="90%" align="center" bgcolor="#efefef">
     <tr><td colspan="2">
     <b>申请注意: </b>
     <ol>
     <li>本站服务旨在促进中国 BBS 发展，增进 BBS 间交流，方便广大 BBS 站而设置，不欢迎不开或未开 BBS的朋友来申请 $syscfg[dn] 域名。</li>
     <li>本站不欢迎一个 BBS 同时申请多个域名。
     </li>
     <li>本站随时欢迎大家加入中国 BBS 转信(cn.bbs.*)的大家庭，您可以在域名注册通过后方便的通过本站申请转信。
     </li>
     <li>本站保留随时终止或修改此项服务的权利。</li>
     <li>如果您已经详细阅读以上条款并完全同意，请继续注册。</li>
     </ol>
     </td></tr>
     <tr><td colspan="2" align="center"><font color="red"><b>注意: $msg</b></font></td></tr>
     <form action="$PHP_SELF" method="post">
     <tr>
     <td nowrap align="right"><b>申请域名: </b></td>
     <td>
     <input type="text" size="14" maxlength="12" name="name" value="$name"><b>.$syscfg[dn]</b>
     </td>
     </tr>
     <tr><td></td><td>
     <ol>
       <li>请输入由字母(a-z)、数字(0-9)以及短横线(-)组成的长度为2-12的字符串(首尾不得为-)。</li>
       <li><font color="red">如果您想申请转信，请注意输入内容的大小写。如: SMTH</font></li>
      <li>如果您已经加入转信，请注意输入内容必须同 ~bbs/innd/bbsname.bbs 相同。或者在联系本站管理员后，按照上面输入内容修改 ~bbs/innd/bbsname.bbs 。</li>
      <li>如果您尚未加入转信，您可以自己选择，建议您申请简单易懂、简短易记的域名。如: SMTH 而不是 SMTH-BBS 或者 SMTHBBS</li>
       <li>所输内容同时作为登录账号。</li>
     </ol>
     </td>
     </tr>
     <tr><td nowrap align="right"><b>登录密码: </b></td>
     <td>
	   <input type="password" name="password" size="14" maxlength="12">
     </td>
     </tr>
     <tr><td nowrap align="right"><b>再次确认密码: </b></td>
     <td>
	   <input type="password" name="password2" size="14" maxlength="12">
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
     <tr><td nowrap align="right"><b>电子信箱: </b></td>
     <td><input type="text" size="40" maxlength="60" name="email" value="$email"></td>
     </tr>
     <tr><td></td><td>
	   请输入常用电子信箱(非BBS信箱)便于联系，据此发送激活认证码及其它通知信件。
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
     <td><input type="text" size="40" maxlength="60" name="mx1" value="$mx1"> (MX1，如果您不明白此项含义，请勿填写。)</td>
     </tr>
     <tr><td nowrap align="right"><b>Mail eXchange 2: </b></td>
     <td><input type="text" size="40" maxlength="60" name="mx2" value="$mx2"> (MX2，如果您不明白此项含义，请勿填写。)</td>
     </tr>
     <tr><td nowrap align="right"><b>Wildcard: </b></td>
     <td><input type="checkbox" name="wildcard" value="1"> (如果您不明白此项含义，请勿打勾) </td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的中文站名: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsname" value="$bbsname"> (空格请用_代替。如: BBS_水木清华站)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的连接端口: </b></td>
     <td><input type="text" size="10" maxlength="8" name="bbsport" value="$bbsport"> (如: 23)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的所属单位: </b></td>
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
     <td><input type="text" size="20" maxlength="16" name="bbsid" value="$bbsid"> (请输入最常用的ID。如: hightman)</td>
     </tr>
     <tr><td nowrap align="right" valign="top"><b>您的BBS的内容及特色: </b></td>
     <td><textarea name="introduce" cols="60" rows="10">$introduce</textarea></td>
     </tr>
     $num_auth
     <tr><td align="center" colspan="2">
     <input type="submit" name="submit" value=" 确 定 ">
     <input type="reset" value=" 重 写 "> 
     </td></tr>
     </form>
     </table>
__EOF__;
}

$action = "register";
include ("header.php");
print $string;
include ("footer.php");
exit();

?>
