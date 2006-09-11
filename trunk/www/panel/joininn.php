<?php
// join maillist
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id$

include ("config.php");
if (!is_login()) {
    header("Location: loginout.php");
    exit();
}

require ("db_mysql.php");
require ("funcs.php");

// Create Mysql Class
//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();

$varibles = array('innhost', 'innport');
foreach ($varibles as $tmp) {
    ${$tmp} = &cgi_var($tmp);
}

$name = &my_session_get('dns_name');
$pass = &my_session_get('dns_pass');

$msg = "";
$submit = &cgi_var('submit');
if (!isset($submit)) 
    $msg = "请认真填写以下各项！";
elseif (!is_dn($innhost))
    $msg = "BBS域名填写不合规定！";

if ($msg == "") {
    $groups = &cgi_var('groups');
    $boards = &cgi_var('boards');
    $tmpgrp = array();

    settype($groups, "array");
    settype($boards, "array");

    foreach($groups as $key => $value) {
	if (is_numeric($groups[$key]) && !empty($boards[$key])) {
	    $tmpgrp[$groups[$key]] = $boards[$key];
	}	
    }

    $groups = serialize($tmpgrp);

    $xmode = &cgi_var('xmode');
    $attach = "";
    if ($xmode & (_INN_ACTIVE_|_INN_PASSIVE_))
	$xmode = 0;
    else {
	$xmode = _INN_ACTIVE_;
	$attach = ", innsrv = -1";
    }

    if (!$innport) $innport = 7777;
	
	$db->query_first("SELECT innhost, innport, groups FROM _my_dns WHERE name = '$name' LIMIT 1");
	if(($innhost == $db->f("innhost")) && ($innport == $db->f("innport")) && ($groups == $db->f("groups"))) {
		$string = "<p align=\"center\"><span style=\"color:#FF0000\">您没有作任何修改。</span>如果您以前提交过转信申请，那么您以前提交的申请已经取消。<a href=\"joininn.php\">点击这里返回</a>。</p>\n";
		$db->query("DELETE FROM _inn_req WHERE username = '$name' AND agree=0");
	}
	else {
		$groups = addslashes($groups);
		$db->query("DELETE FROM _inn_req WHERE username = '$name' AND agree=0");
		$db->query("INSERT INTO _inn_req (username,newinnhost,newinnport,newgroups,reqtime) VALUES ('$name','$innhost',$innport,'$groups',now())");

		$db->query("UPDATE _my_dns SET  xmode = (xmode | $xmode)" . $attach . " WHERE pass = '$pass' AND name = '$name'");

		$string = "<br><p align=\"center\"><font color=\"red\" size=\"5\"><b>申请/修改已经提交！</b></font><br><br>\n"
		. "(请参考<a href=\"innconf.php\">转信配置提示</a>) </p>";
		if ($xmode) {
			$string .= "<p align=\"center\"><font color=\"red\" style=\"font-size: 16px\"><b>注意！</b></font>"
			. "欢迎您加入中国 BBS 转信(cn.bbs.*)的大家庭，在管理员审核通过后，将发信通知您。请您在见信后，在 <a href=\"http://webnews.cn-bbs.org/group//cn.bbs.admin.test\" target=\"_blank\">cn.bbs.admin.test</a> 组进行转入/转出/跨站砍信测试，任何问题请参阅 <a href=help.php>F.A.Q.</a> 或在 <a href=\"http://webnews.cn-bbs.org/group//cn.bbs.admin\" target=\"_blank\">cn.bbs.admin</a> 组寻求帮助。祝您顺利！</p>\n";
		}
		else {
			$string .= "<p align=\"center\"><font color=\"red\" style=\"font-size: 16px\"><b>注意！</b></font>"
			. "您已经提交了转信申请，在管理员审核通过后，将发信通知您。</p>\n";
		}
	}
}
else {
	$db->query("SELECT _inn_req.id,username,newinnhost,newinnport,newgroups,reqtime,innhost,innport,groups FROM _inn_req,_my_dns WHERE agree=0 AND _inn_req.username=_my_dns.name AND name='$name' LIMIT 1");
	if($tmp = $db->fetch_array()) {
		$db2 = new db_mysql($syscfg['mysql']);
		$db2->connect();
		$db2->query("SELECT * FROM _news_grp WHERE type!=2 ORDER BY type");
		$resubmit = true;
		$resubmit_notice = "<tr><td colspan=\"2\"><div align=\"center\" style=\"text-align:left;border:1px solid #FF6600;padding:10px\"><p>您已经提交过一份转信申请如下，管理员尚未审核，您如果再次提交申请将覆盖上次的申请。</p>";
		$resubmit_notice .= display_inn_req($tmp, $db2);
		$resubmit_notice .= "</div></td></tr>";
		$db2->close();
		
	}
	else {
		$resubmit = false;
		$resubmit_notice = "";
	}
    $db->query_first("SELECT host, xmode, innhost, innport, innsrv, groups FROM _my_dns WHERE name = '$name' LIMIT 1");
    $oldgrp = $db->f('groups');
    if (!$innhost) $innhost = $db->f('innhost');
    if (!$innport) $innport = $db->f('innport');
    if (!$innport) $innport = 7777;
//    if (empty($innhost)) $innhost = $name . $syscfg['dn'];
    $oldgrp = unserialize($oldgrp);
    $xmode = $db->f('xmode');
    $innsrv = $db->f('innsrv');
    $passive_msg = "";

    if (!($xmode & (_INN_PASSIVE_|_INN_ACTIVE_)))
	$inntype = "您尚未设置转信！";
    elseif ($innsrv == 0)
	$inntype = "没有转信！";
    elseif ($innsrv == -1)
	$inntype = "测试成员申请中！";
    elseif ($innsrv <= -2)
	$inntype = "正式成员申请中！";
    elseif ($xmode & _INN_PASSIVE_)
	$inntype = "正式成员！";
    elseif ($xmode & _INN_ACTIVE_) {
	$inntype = "测试成员！";
	$passive_msg = "<a href=\"join_passive.php\">申请正式成员！</a>";
    }

    $db->query("SELECT * FROM _news_grp WHERE type != 2 ORDER BY type");

    $groups = "\t<table border=\"1\" cellspacing=\"0\">\n"
    ."\t<tr align=\"left\"><th>cn.bbs.* 新闻组名</th><th>对应的 BBS 英文版名</th></tr>\n"
    ."\t<tr><th colspan=\"2\" align=\"left\"> *** 必须转的组 *** </th></tr>\n";

    $i = 1;
    $lastype = 0;

    while ($tmp = $db->fetch_array()) {
	if ($tmp['type'] != $lastype)
	    $groups .= "\t<tr><th colspan=\"2\" align=\"left\"> *** 选择转的组 *** </th></tr>\n";
	
	$lastype = $tmp['type'];

	if (isset($oldgrp[$tmp['id']])) {
	    $checked = " checked";
	    $board = $oldgrp[$tmp['id']];
	}
	else {
	    $checked = "";
	    $board = "";
	}

	if ($lastype == 0) {
	    $groups .= "\t<tr><td><input type=\"hidden\" name=\"groups[$i]\" value=\"" . $tmp['id'] . "\">·" 
	    . $tmp['name'] . " (" . $tmp['title'] . ")</td>\n";
	}
	else
	    $groups .= "\t<tr><td><input type=\"checkbox\" name=\"groups[$i]\" value=\"" . $tmp['id'] . "\"" 
	    . $checked . ">" . $tmp['name'] . " (" . $tmp['title'] . ")</td>\n";

	$groups .= "\t<td><input type=\"text\" name=\"boards[$i]\" size=\"16\" maxlength=\"18\" value=\"" 
	. $board . "\">(注意大小写)</td></tr>\n";
	$i++;
    }

    $groups .= "\t</table>\n";

    $string = <<<__EOF__
     <table border="0" width="100%" align="center" bgcolor="#efefef">
     <tr><td colspan="2">
	您目前的转信状态: <font style="font-size: 14px" color="red"><b>$inntype</b></font> $passive_msg
     </td></tr>
     <tr><td colspan="2">
     <b>申请注意: </b>
     <ol>
     <li>本站随时欢迎全国各地 BBS 加入中国 BBS 转信(cn.bbs.*)的大家庭。</li>
     <li>转信会给版面带来一定的冲击，希望管理员在转信之前充分考虑，既能够跟众多的 BBS 交流，又能够保持自己的特色。不鼓励将所有版都纳入转信。</li>
     <li>请阅读<a href="http://cn-bbs.org/doc/CHARTER" target="_blank">《cn.bbs.* 章程》</a>。</li>
     <li>本站保留随时终止或修改此项服务的权利。</li>
     <li>如果您已经详细阅读以上条款并完全同意，请继续申请。</li>
     </ol>
     </td></tr>
{$resubmit_notice}
     <tr><td colspan="2" align="center"><font color="red"><b>注意: $msg</b></font></td></tr>
     <form action="$PHP_SELF" method="post">
     <tr><td nowrap align="right"><b> BBS域名: </b></td>
     <td><input type="text" size="40" maxlength="32" name="innhost" value="$innhost"></td>
     <tr><td></td><td>
     <ol>
       <li>请输入您的 BBS 正式使用的域名。如: smth.edu.cn 或者 29bbs.cn-bbs.org</li>
     </ol>
     </td></tr>
     </tr>
     <tr><td nowrap align="right"><b> innbbsd端口: </b></td>
     <td><input type="text" size="8" maxlength="8" name="innport" value="$innport"></td>
     <tr><td></td><td>
     <ol>
       <li>请输入 innbbsd 监听端口，默认为 7777。</li>
     </ol>
     </td></tr>
     </tr>
      </td>
     </tr>
     <tr><td nowrap align="right"><b>选择参加转信的组: </b></td>
     <td>
	   以下仅为 cn.bbs.* 部分，在您通过注册之后，将可以加入 4000 左右个转信组。
     </td>
     </tr>
     <tr><td></td>
     <td>
	$groups
     </td>
     </tr>
     <tr><td align="center" colspan="2">
     <input type="hidden" name="xmode" value="$xmode">
     <input type="submit" name="submit" value=" 确 定 ">
     <input type="reset" value=" 重 写 "> 
     </td></tr>
     </form>
     </table>
__EOF__;
}

$db->close();

$action = "joininn";
include ("header.php");
print $string;
include ("footer.php");
exit();

?>
