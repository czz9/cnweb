<?php
//$Id$

require("../funcs.php");

function listnews() {
	global $syscfg, $string;
	
	require("../db_mysql.php");
// Create Mysql Class
//    $db1 = db_mysql::connect($syscfg['mysql']);
	$db1 = new db_mysql($syscfg['mysql']);
    $db1->connect();
//    $db2 = db_mysql::connect($syscfg['mysql']);
	$db2 = new db_mysql($syscfg['mysql']);
    $db2->connect();

    $newslist = "<table border=\"1\" cellspacing=\"0\">\n"
    ."<tr align=\"center\"><th>服务器域名</th><th>FQDN/IP</thu><th>使用该服务器的BBS (正式成员数/所有成员数)</th><th>服务对象</th></tr>\n";

    $db1->query("SELECT * FROM _news_srv ORDER BY name");
    while ($db1->next_record()) {
	if ($db1->f('url')) 
	    $newslist .= "<tr><td><a name=\"" . $db1->f('id'). "\" href=\"" . $db1->f('url') . "\" target=\"_blank\">" . $db1->f('name') . "</a></td><td>" . $db1->f('host') . "</td>\n<td>";
	else
	    $newslist .= "<tr><td>" . $db1->f(name) . "</td><td>" . $db1->f(host) . "</td>\n<td>";
	$sum = 0;
	$sum_passive = 0;
	$db2->query("SELECT id, name, xmode FROM _my_dns WHERE innsrv = " .  $db1->f('id') . " ORDER BY name");
	while ($db2->next_record()) {
	    $sum = $sum + 1;
	    if ($db2->f('xmode') & (_INN_PASSIVE_)) {
		$sum_passive = $sum_passive + 1;
	        $newslist .= "<a href=../query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a> ";
	    } else
	        $newslist .= "<I><a href=../query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a></I> ";
	}
	$newslist .= "(" . $sum_passive . "/" . $sum . ")";
	$newslist .= "</td><td>" . $db1->f('comment') . "</td></tr>\n";
        $db2->close();
    }
    $db1->close();

    $newslist .= "</table>\n";

    $string .= <<<__EOF__
     <table border="0" align="center" bgcolor="#efefef">
     <tr>
     <td>
	$newslist
     </td>
     </tr>
     </table>
__EOF__;

}

function listgroups() {
	global $syscfg, $string;
	
	require("../db_mysql.php");
// Create Mysql Class
//    $db1 = db_mysql::connect($syscfg['mysql']);
	$db1 = new db_mysql($syscfg['mysql']);
    $db1->connect();
//    $db2 = db_mysql::connect($syscfg['mysql']);
	$db2 = new db_mysql($syscfg['mysql']);
	$db2->connect();

    $grouplist = "<table border=\"1\" cellspacing=\"0\">\n"
    ."<tr align=\"center\"><th>英文组名</th><th>中文描述</thu><th>转该组的BBS (正式成员数/所有成员数)</th></tr>\n";

    $db1->query("SELECT * FROM _news_grp WHERE type != 2 ORDER BY name");
    while ($db1->next_record()) {
	$grouplist .= "<tr><td><a name=\"" . $db1->f('name') . "\" href=\"http://webnews.cn-bbs.org/group//" . $db1->f('name') . "\" target=\"_blank\">" . $db1->f('name') . "</a></td><td>" . $db1->f('title') . "</td>\n<td>";
	$sum = 0;
	$sum_passive = 0;
	$db2->query("SELECT id, name, xmode FROM _my_dns WHERE innsrv != 0 AND groups RLIKE '.*i\:" . $db1->f('id') . "\;s\:.*' ORDER BY name");
	while ($db2->next_record()) {
	    $sum = $sum + 1;
	    if ($db2->f('xmode') & (_INN_PASSIVE_)) {
		$sum_passive = $sum_passive + 1;
		$grouplist .= "<a href=../query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a> ";
	    } else
		$grouplist .= "<I><a href=../query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a></I> ";
	}
	$grouplist .= "(" . $sum_passive . "/" . $sum . ")";
	$grouplist .= "</td></tr>\n";
        $db2->close();
    }
    $db1->close();

    $grouplist .= "</table>\n";

    $string .= <<<__EOF__
     <table border="0" align="center" bgcolor="#efefef">
     <tr>
     <td>
	$grouplist
     </td>
     </tr>
     </table>
__EOF__;

}

function adm_default() {
    global $syscfg, $string;
	
	$size = filesize($syscfg['logfile']);
	$fp = fopen($syscfg['logfile'], "r");
	$adminlog = "";
	if($fp != NULL) {
		if($size > 512) {
			fseek($fp, $size - 512);
			fgets($fp);
		}
		while(!feof($fp))
			$adminlog .= fgets($fp) . "<br />\n";
		fclose($fp);
	}
	
    $string .= <<<__EOF__
	<center><font color="red">注意: </font>由于时间关系，其它方面的管理暂时没写，请配合 phpMyAdmin 来完成！</center>
	<ul type="square">
	<li> 保留域名表是:  _keep_dns 
	<blockquote>name: 域名(不加域,如 hightman即可), type: 记录类型，如 (A), 
	<br>target: 目标域名，最后必须加dot[.], 如果是 MX 记录，格式为:  100|mx.host. 如果不写 100 自动设为 100. </blockquote></li>
	<li> 新闻组名表是: _news_grp
	<blockquote>name: 组名(如 cn.bbs.admin), 
	<br>type: 类型 (0|1|2 , 0: 必转组, 1: 选转组, 2: 秘密组(如tw.xxx)), 
	<br>title: 组的中文描述 (如 cn.bbs管理)</blockquote></li>
	<li> 新闻组服务器表是: _news_srv
	<blockquote>host: 地址. 用 ip[:port] , 如: 166.111.4.19, 166.111.4.19:119 都是对的
	<br>name: Server 域名，如 news.maily.cic.tsinghua.edu.cn </blockquote></li>
	<li> 强行修改用户资料后用户通过认证，且自动激活！<br />&nbsp;</li>
	<li> 最近管理操作记录 <br />
	<blockquote>{$adminlog}</blockquote>
	</li>
	</ul>
__EOF__;
}

function gen_innd() {
    global $syscfg, $string;
    
    require("../db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();

    $innsrv = &cgi_var('innsrv');

    if (!isset($innsrv)) {
	$innsrv_list = "";
	
	$db->query("SELECT id, name FROM _news_srv");
	while ($db->next_record())
	    $innsrv_list .= "<option value=\"" . $db->f('id') . "\">" . $db->f('name') . "</option>\n";
	$db->close();

	$string .= <<<__EOF__
	    <script language="javascript">
	    function do_sub(obj) {
	        obj.innaddr.value = obj.innsrv.options[obj.innsrv.selectedIndex].innerText;
	    }
	    </script>
	    <p align="center"><form action="" . $_SERVER"['PHP_SELF']" . "" method="post" onsubmit="do_sub(this)">
	    <input type="hidden" name="do" value="gen_innd">
	    <input type="hidden" name="innaddr" value="">
	    选择新闻组服务器: <select name="innsrv" size="1">
	    $innsrv_list
	    </select> <input type="submit" value="确定">
	    </form></p>
__EOF__;
	return;
    }

    $string .= "<table width=\"80\"><tr><td><pre>\n";

    $db->query("SELECT id, name FROM _news_grp");
    $groups = array();
    while($db->next_record()) {
	$id = $db->f('id');
	$name = $db->f('name');
	$groups[$id] = $name;
    }

    $inn_mode = _INN_PASSIVE_;
    $valid = _HOST_ACTIVE_;
    $db->query("SELECT name, host, innhost, innport, groups FROM _my_dns WHERE (xmode & $inn_mode) AND (xmode & $valid) AND innsrv = '$innsrv' AND groups != '' ORDER BY host");
    $inn_num = $db->nf();

    $innaddr = &cgi_var('innaddr');

    $tmp_str1 = "\nNews Server: " . $innaddr . " 的配置文件大致如下: \n\n# -----------------------------------------\n"
    . "# ~/etc/incoming.conf\n"
    . "# -----------------------------------------\n"; // incoming.conf
    $tmp_str2 = "\n# -----------------------------------------\n"
    . "# ~/etc/newsfeeds\n"
    . "# -----------------------------------------\n"; // newsfeeds
    $tmp_str3 = "\n# -----------------------------------------\n"
    . "# ~/etc/innfeed.conf\n"
    . "# -----------------------------------------\n"; // innfeed.conf

    while($tmp = $db->fetch_array()) {
	$tmp['groups'] = unserialize($tmp['groups']);
	settype($tmp['groups'], "array");
	$mygrps1 = array();
	$mygrps2 = array();
	foreach($tmp['groups'] as $key => $value) {
	    $mygrps2[] = $groups[$key];
	    if ($groups[$key] != "cn.bbs.admin.announce" && $groups[$key] != "cn.bbs.admin.lists" && $groups[$key] != "cn.bbs.admin.lists.weather" && $groups[$key] != "cn.bbs.campus.tsinghua.news")
		$mygrps1[] = $groups[$key];
	}

	sort($mygrps1);
	sort($mygrps2);
	reset($mygrps1);
	reset($mygrps2);
	$mygrps1 = implode(', ', $mygrps1);
	$mygrps2 = implode(',', $mygrps2);

	$tmp_str1 .= <<<__EOF__
peer $tmp[name] {
	hostname: $tmp[host]
	patterns: "!*, $mygrps1"
}

__EOF__;
	$tmp_str2 .= $tmp['name'] . "\\\n\t:" . $mygrps2 . "\\\n\t:Tm:innfeed!\n";
	if ($tmp['innport'] != 7777) {
		$tmp_str3 .= <<<__EOF__
peer $tmp[name] {
	ip-name:		$tmp[host]
	port-number:	$tmp[innport]
}

__EOF__;
	}
	else {
		$tmp_str3 .= <<<__EOF__
peer $tmp[name] {
	ip-name:		$tmp[host]
}

__EOF__;
	}
    }
    $db->close();
    $tmp_str1 .= $tmp_str2 . $tmp_str3;
    $tmp_str1 .= "\n\n# Total: " . $inn_num . "\n";
    $string .= htmlspecialchars($tmp_str1);
    $string .= "</pre></td></tr></table>\n";
}

function gen_named() {
    global $syscfg, $string;
    
    $fd = @fopen($syscfg['fpath'], 'w');
    if (!$fd) {
	$string .= "无法打开文件: [" . $syscfg['fpath'] . "] !\n";
	return;
    }
    $serial = time();
    $tmp_str = @file($syscfg['ipath']);
    $tmp_str = implode('', $tmp_str);
    eval("\$tmp_str = \"$tmp_str\";");
    $tmp_str = trim($tmp_str);
    $tmp_str = str_replace("\r", "", $tmp_str);

    require("../db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);    
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();

    $string .= "<table width=\"80\"><tr><td><pre>\n";
    $db->query("SELECT name, target, type FROM _keep_dns");
    while($db->next_record()) {
	$name = $db->f('name');
	$target = $db->f('target');
	$type = $db->f('type');
	if ($type == 'MX') list($pref, $target) = explode('|', $target, 2);
	if (!$pref) $pref = 10;
	$tmp_str .= "\n" . $name . "\tIN\t" . $type . "\t" . $pref . "\t" . $target;
    }

    $valid = _HOST_ACTIVE_;
    $active = _ACCT_ACTIVE_;
    $db->query("SELECT name, host, mx1, mx2, ns1, ns2, xmode FROM _my_dns WHERE (xmode & $valid) AND (xmode & $active)");
    while($tmp = $db->fetch_array()) {
	$mydns = "";
	$tmp['name'] = strtolower($tmp['name']);
	if (is_ip($tmp['host']) && empty($tmp['ns1']))
	    $mydns = "\n" . $tmp['name'] . "\t\tA\t\t" . $tmp['host'];
	elseif (is_dn($tmp['host']))
	    $mydns = "\n" . $tmp['name'] . "\t\tCNAME\t\t" . $tmp['host'] . ".";
	if (!empty($tmp['mx1']) && !is_dn($tmp['host']) && empty($tmp['ns1']))
	    $mydns .= "\n" . $tmp['name'] . "\t\tMX\t10\t" . strtolower($tmp['mx1']) . ".";
	if (!empty($tmp['mx2']) && !is_dn($tmp['host']) && empty($tmp['ns1']))
	    $mydns .= "\n" . $tmp['name'] . "\t\tMX\t20\t" . strtolower($tmp['mx2']) . ".";
	if (!empty($tmp['ns1']) && !is_dn($tmp['host'])) {
	    if (is_ip($tmp['ns1']))
		$mydns .= "\n" . $tmp['name'] . "\t\tNS\t\tns1." . $tmp['name']
		. "\nns1." . $tmp['name'] . "\t\tA\t\t" . $tmp['ns1'];
	    else
		$mydns .= "\n" . $tmp['name'] . "\t\tNS\t\t" . strtolower($tmp['ns1']) . ".";
	}
	if (!empty($tmp['ns2']) && !is_dn($tmp['host'])) {
	    if (is_ip($tmp['ns2']))
		$mydns .= "\n" . $tmp['name'] . "\t\tNS\t\tns2." . $tmp['name']
		. "\nns2." . $tmp['name'] . "\t\tA\t\t" . $tmp['ns2'];
	    else
		$mydns .= "\n" . $tmp['name'] . "\t\tNS\t\t" . strtolower($tmp['ns2']) . ".";
	}
	if ($tmp['xmode'] & _HAVE_WILDCARD_)
	    $mydns .= "\n*." . $tmp['name'] . "\t\tCNAME\t\t" . $tmp['name'];

	$tmp_str .= $mydns;
    }

    $tmp_str = $tmp_str . "\n";
    fputs($fd, $tmp_str);
    fclose($fd);
    $db->close();

    $string .= htmlspecialchars($tmp_str);
    $string .= "</pre></td></tr></table>\n";
}

function reload_named() {
    global $syscfg, $string;

    $cmd = $syscfg['rprog'] . " " . $syscfg['fpath'] . " " . $syscfg['dpath'] . " " . $syscfg['uid'] . " " . $syscfg['gid'] . " " . $syscfg['restart'];

    exec($cmd, $output, $return);
    $string .= "<pre>\n"
    . "Cmd: " . $cmd . "\n"
    . "Result: " . implode('', $output) . "\n"
    . "Return Values: " . $return . "</pre>";
}

function check_dns() {
    global $syscfg, $string, $PHP_SELF;

    require("../db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);    
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();

    $id = &cgi_var('id');
    $r = &cgi_var('r');
    $valid = _HOST_ACTIVE_;
    $active = _ACCT_ACTIVE_;
    if (!isset($id)) { //list.
        $db->query("SELECT id, name, host, bbsname, bbsid, bbsport FROM _my_dns WHERE !(xmode & $valid) AND (xmode & $active)");
	$total = $db->nf();
	$string .= "<ul type=\"square\">\n";
	while ($tmp = $db->fetch_array()) {
	    $string .= "<li><a href=\"" . $PHP_SELF . "?do=check_dns&id=" . $tmp['id'] . "\">" 
	    . $tmp['name'] . "." . $syscfg['dn'] . "</a> &lt;=&gt; <a href=\"telnet:" 
	    . $tmp['host'] . ":" . $tmp['bbsport'] . "\">" .  $tmp['host'] . "</a>( 站名: " 
	    . $tmp['bbsname'] . " | 申请人: " . $tmp['bbsid'] . " )</li>\n";
	}
	$string .= "</ul>\n<p> 目前共有 " . $total . " 条记录未被审核！\n";
    }
    elseif (!isset($r)) { // id..
	$tmp = $db->query_first("SELECT name, host, mx1, mx2, ns1, ns2, xmode, bbsname, bbsport, bbsdept, bbsonline, bbslogin, bbsid, email, innsrv, introduce FROM _my_dns WHERE id = '$id' OR name = '$id' LIMIT 1");

	if (!is_array($tmp)) {
	    $string .= "查询出错，找不到登录名称为 " . $id . " 的记录！";
	    $db->close();
	    return;
	}

	if ($tmp['xmode'] & _INN_PASSIVE_) $checked = " selected";
	$inntype_list = "<option value=\"0\">主动式</option>\n<option value=\"1\"" . $checked . ">被动式</option>\n";
	$innsrv_list = "<option value=\"0\">不让它转信</option>";
	
	$db->query("SELECT id, name FROM _news_srv");
	while ($db->next_record()) {
	    if ($db->f('id') == $tmp['innsrv'])
		$innsrv_list .= "<option value=\"" . $db->f('id') . "\" selected>" . $db->f('name') . "</option>\n";
	    else
		$innsrv_list .= "<option value=\"" . $db->f('id') . "\">" . $db->f('name') . "</option>\n";
	}

	if ($tmp['xmode'] & _HAVE_WILDCARD_) $checked = " checked";
	else $checked = "";
	$tmp['introduce'] = stripslashes($tmp['introduce']);
	$tmp['bbsname'] = stripslashes($tmp['bbsname']);
	$tmp['bbsdept'] = stripslashes($tmp['bbsdept']);
	$tmp['bbsonline'] = stripslashes($tmp['bbsonline']);
	$tmp['bbslogin'] = stripslashes($tmp['bbslogin']);
	
	$string = <<<__EOF__
     <table border="0" width="90%" align="center" bgcolor="#efefef">
     <form action="$PHP_SELF" method="post">
     <tr>
     <td nowrap align="right"><b>申请域名: </b></td>
     <td>
     <input type="text" size="14" maxlength="12" name="name" value="$tmp[name]"><b>.$syscfg[dn]</b>
     </td>
     </tr>
     <tr><td></td><td>
     <ol>
       <li>请输入由字母(a-z)、数字(0-9)以及短横线(-)组成的长度为2-12的字符串(首尾不得为-)。</li>
       <li><font color="red">如果您想申请转信，请注意输入内容的大小写。如: SMTH</font></li>
       <li>如果您已经加入转信，请注意输入内容必须同 ~bbs/innd/bbsname.bbs 相同。或者在联系本站管理员后，按照上面输入内容修改 ~bbs/innd/bbsname.bbs 。</li>
       <li>如果您尚未加入转信，您可以自己选择，建议您申请简单易懂、简短易记的域名。如: SMTH 而不是 SMTH-BBS 或者 SMTHBBS</li>
       <li>所输内容同时作为登录账号。</li>                                                            </ol>
     </td>
     </tr>
     <tr>
     <td nowrap align="right"><b>BBS地址: </b></td>
     <td><input type="text" size="40" maxlength="32" name="host" value="$tmp[host]">
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
     <td><input type="text" size="40" maxlength="60" name="email" value="$tmp[email]"></td>
     </tr>
     <tr><td></td><td>
	   请输入常用电子信箱(非BBS信箱)便于联系，据此发送激活认证码及其它通知信件。
     </td></tr>
     <tr><td nowrap align="right"><b>主域名服务器: </b></td>
     <td><input type="text" size="40" maxlength="60" name="ns1" value="$tmp[ns1]"> (即 NS1)</td>
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
     <td><input type="text" size="40" maxlength="60" name="ns2" value="$tmp[ns2]"> (即 NS2)</td>
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
     <td><input type="text" size="40" maxlength="60" name="mx1" value="$tmp[mx1]"> (MX1，如果您不明白此项含义，请勿填写。)</td>
     </tr>
     <tr><td nowrap align="right"><b>Mail eXchange 2: </b></td>
     <td><input type="text" size="40" maxlength="60" name="mx2" value="$tmp[mx2]"> (MX2，如果您不明白此项含义，请勿填写。)</td>
     </tr>
     <input type="hidden" name="xmode" value="$tmp[xmode]">
     <tr><td nowrap align="right"><b>Wildcard: </b></td>
     <td><input type="checkbox" name="wildcard" value="1" $checked> (如果您不明白此项含义，请勿打勾) </td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的中文站名: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsname" value="$tmp[bbsname]"> (如: BBS_水木清华站)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS的连接端口: </b></td>
     <td><input type="text" size="10" maxlength="8" name="bbsport" value="$tmp[bbsport]"> (如: 23)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS所属单位: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsdept" value="$tmp[bbsdept]"> (如: 清华大学)</td>
     </tr>
     <tr><td nowrap align="right"><b>BBS在线人数前导词: </b></td>
     <td><input type="text" size="40" maxlength="32" name="bbsonline" value="$tmp[bbsonline]"></td>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于根据进站画面统计在线人数。</li>
       <li><font color="red">如果您使用 Firebird 且未修改进站部分，本栏默认内容为“目前上站人数”。</font></li>
      <li>如果您的BBS在线人数前导词与默认值不同，请根据实际情况填写。如: “上线人数”、“当前在线”、“目前站内石头”、“目前轩上游客”、“目前在城内闲逛的石头”等。</li>
     </ol>
     </td></tr>
     </tr>
     <tr><td nowrap align="right"><b>BBS登录进站前导词: </b></td>
     <td><input type="text" size="10" maxlength="8" name="bbslogin" value="$tmp[bbslogin]"></td>
     <tr><td></td><td>
     <ol>
       <li>此项数据将用于根据进站画面统计在线人数。</li>
       <li><font color="red">目前国内绝大多数的 BBS 已不需要在登录时输入 bbs ，故此栏可以不填写。</font></li>
      <li>如果您的BBS登录进站前导词与默认值不同，请根据实际情况填写。如: bbs</li>
     </ol>
     </td></tr>
     </tr>
     <tr><td nowrap align="right"><b>您在您的BBS上的ID: </b></td>
     <td><input type="text" size="20" maxlength="16" name="bbsid" value="$tmp[bbsid]"> (请输入最常用的ID。如: hightman)</td>
     </tr>
     <a name="innset">
     <tr><td nowrap align="right" valign="top"><b>指定News Server: </b></td>      
     <td><select name="innsrv" size="1">
     $innsrv_list
     </select>
     <select name="inntype" size="1">
     $inntype_list
     </select>
     <input type="hidden" name="oinnsrv" value="$tmp[innsrv]">
     </td>
     </tr>
     </a>
     <tr><td nowrap align="right" valign="top"><b>您的BBS的内容及特色: </b></td>
     <td><textarea name="introduce" cols="60" rows="10">$tmp[introduce]</textarea></td>
     </tr>
     <tr><td nowrap align="right" valign="top"><b>处理意见: </b></td>
     <td>
	<input type="radio" name="r" value="ok" checked>通过
	<input type="radio" name="r" value="del"><font color="red">删除！</font>
     </td>
     </tr>	   
     <tr><td align="center" colspan="2">
     <input type="hidden" name="id" value="$id">
     <input type="hidden" name="do" value="check_dns">
     <input type="submit" name="submit" value=" 确 定 ">
     <input type="reset" value=" 重 写 "> 
     </td></tr>
     </form>
     </table>
__EOF__;
    }
    elseif ($r == 'del') {
	$db->query("DELETE FROM _my_dns WHERE id = '$id'");
	cnweb_log("deleted user {$id}.");
	$string .= "<p align=\"center\"><font size=\"4\"><b>删除成功！！</b></font> <br><br>\n"
	. "<a href=\"" . $PHP_SELF . "?do=check_dns\">继续审核</a> <a href=\"../list.php\">返回主页</a></p>\n";
    }
    else {
	$varibles = array('name', 'email', 'host', 'mx1', 'mx2', 'ns1', 'ns2', 'bbsid', 'bbsname', 'bbsport', 'bbsdept', 'bbsonline', 'bbslogin', 'xmode', 'wildcard', 'innsrv', 'oinnsrv', 'inntype');
	if (!$bbsport) $bbsport = 23;
	
	foreach ($varibles as $tmp) {
	    ${$tmp} = &cgi_var($tmp);
	}

	$mail_to = 1;
	if ($xmode & _HOST_ACTIVE_)
	    $mail_to = 0;

	$introduce = &cgi_var('introduce');
	if ($wildcard == 1)
	    $xmode |= _HAVE_WILDCARD_;
	else
	    $xmode &= ~_HAVE_WILDCARD_;

	$xmode |= _HOST_ACTIVE_;
	$xmode |= _ACCT_ACTIVE_;

	if ($innsrv == 0) { // 取消转信了. :)
	    $xmode &= ~_INN_PASSIVE_;
	    $xmode &= ~_INN_ACTIVE_;
	}
	elseif ($inntype == 1)
	    $xmode |= _INN_PASSIVE_;
	else
	    $xmode &= ~_INN_PASSIVE_;
	
	if ($mx1 == "") $mx1 = $name . "." . $syscfg['dn'];
	if ($mx2 == $mx1) $mx2 = "";
	if ($ns2 == $ns1) $ns2 = "";
	
	$db->query("UPDATE _my_dns SET name = '$name', host = '$host', mx1 = '$mx1', mx2 = '$mx2', ns1 = '$ns1', ns2 = '$ns2', xmode = '$xmode', bbsname = '$bbsname', bbsport = '$bbsport', bbsdept = '$bbsdept', bbsonline = '$bbsonline', bbslogin = '$bbslogin', innsrv = '$innsrv', bbsid = '$bbsid', email = '$email', introduce = '$introduce' WHERE id = '$id' OR name = '$id'");
	cnweb_log("modified user info, id={$id}.");
	$string .= "<p align=\"center\"><font size=\"4\"><b>修改成功！！</b></font> <br><br>\n"
	. "<a href=\"" . $PHP_SELF . "?do=check_dns\">继续审核</a> <a href=\"../list.php\">返回主页</a></p>\n";

	$mhdr = "From: " . $syscfg['email'] . "\r\nReply-To: " . $syscfg['email'] . "\r\nX-Mailer: php program by hightman.";

	if ($mail_to == 1) {
		cnweb_log("granted registration of user {$id}.");
	    $now = date("Y-m-d H:i:s");       
	    $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    您的域名申请已经审核通过，请您立即登陆我们的网页 $syscfg[url]/loginout.php
管理您的帐号，域名更新将在每日凌晨 03 点 33 分进行，在这之后的几个小时内，
您的域名即可生效，同时您也可以开始申请转信。
    审核时间: $now
--
$syscfg[url]
__EOF__;
	    if(!@mail($email, "[cn-bbs] 恭喜您，您已经完成注册", $mailmsg, $mhdr))
		cnweb_log("failed to send email to {$email}.");
		$string .= "<p><font color=\"red\">警告: 送信到 " . $email . " 失败！</p>\n";
	    
	//mail to blog
	$mailmsg = <<<__EOF__
    来自 $bbsdept 的 $bbsname 已经加入了 CN-BBS.ORG 的大家庭。详细信息如下：
        域名：$name.$syscfg[dn]
        地址：$host
        端口：$bbsport
        申请人："$bbsid" <$email>
        自我介绍：$introduce
__EOF__;
	$mailtitle = "$bbsname($host)加入CN-BBS.ORG";
	if(!@mail($syscfg['blogmail'], $mailtitle, $mailmsg, $mhdr))
		cnweb_log("failed to send email to {$syscfg['blogemail']}."); 
		$string .= "<p><font color=\"red\">警告: 送信到 " . $syscfg['blogmail'] . " 失败！</p>\n";

	    //mail to news..
	    $ansi_begin = "\033[1;33m";
	    $ansi_end = "\033[m";
	    $mailmsg = <<<__EOF__
From: $syscfg[email] (http://cn-bbs.org/)
Newsgroups: $syscfg[newsgrp]
Subject: [cn-bbs] 欢迎新来的 BBS ($bbsname)

致各 BBS 管理员:
    让我们欢迎
       $ansi_begin $bbsdept $ansi_end —— $ansi_begin $bbsname $ansi_end
    加入 CN-BBS.ORG 的大家庭。我们期待着它早日加入中国 BBS 转信(cn.bbs.*)！
    
    它在 CN-BBS.ORG 的统一域名为:$ansi_begin $name.$syscfg[dn] $ansi_end
    BBS地址为:$ansi_begin $host $ansi_end
    端口:$ansi_begin $bbsport $ansi_end
    这里是它的自我介绍: ( by "$bbsid" <$email> )
==
$introduce
==
                                        $syscfg[dn] 管理小组
                                        $now
--
※ Origin: http://$syscfg[dn]/
◆ From: PHP4 Mailer By "hightman" <root@hightman.net>
__EOF__;
	    if ($fp = @popen($syscfg['mpath'], 'w')) {
		fputs($fp, $mailmsg);
		pclose($fp);
	    }
	    else {
			cnweb_log("failed to notify news server.");
			$string .= "<p><font color=\"red\">警告: 送信到 News Server 失败！</p>\n";
		}
	}

	if ($innsrv != $oinnsrv) { // innsrv changed...
	    if ($oinnsrv < 0) { // 申请转信
		if ((($oinnsrv == -1) && $innsrv > 0) || ($xmode & (_INN_PASSIVE_))) {
		    $inntype = ($inntype == 1 ? "正式成员" : "测试成员");
			cnweb_log("granted " . (($inntype==1)?"passive":"active") ." inn request of user {$id}.");
		    $now = date("Y-m-d H:i:s");
	   	    $ansi_begin = "\033[1;33m";
		    $ansi_end = "\033[m";
		    $mailmsg = <<<__EOF__
From: $syscfg[email] (http://cn-bbs.org/)
Newsgroups: $syscfg[newsgrp]
Subject: [cn-bbs] 欢迎 $bbsname BBS 成为$inntype

致各 BBS 管理员:
    让我们欢迎
       $ansi_begin $bbsdept $ansi_end —— $ansi_begin $bbsname $ansi_end
    加入 cn.bbs.* 转信大家庭！

    它在 CN-BBS.ORG 的统一域名为:$ansi_begin $name.$syscfg[dn] $ansi_end
    BBS地址为:$ansi_begin $host $ansi_end
    端口:$ansi_begin $bbsport $ansi_end

    希望 $ansi_begin $bbsname $ansi_end 的加入能推动 cn.bbs.* 更好发展。

                                        $syscfg[dn] 管理小组
                                        $now
--
※ Origin: http://$syscfg[dn]/
◆ From: PHP4 Mailer By "hightman" <root@hightman.net>
__EOF__;
		    if ($fp = @popen($syscfg['mpath'], 'w')) {
			fputs($fp, $mailmsg);
			pclose($fp);
		    }

	//mail to blog
	$mailmsg = <<<__EOF__
    来自 $bbsdept 的 $bbsname 已经成为 cn.bbs.* 转信$inntype。详细信息如下：
        域名：$name.$syscfg[dn]
        地址：$host
        端口：$bbsport
__EOF__;
	$mailtitle = $bbsname . "(" . $host . ")成为 cn.bbs.* 转信" . $inntype;
	$mhdr = "From: " . $syscfg['email'] . "\r\nReply-To: " . $syscfg['email'] . "\r\nContent-Type: text/plain;\tcharset=\"UTF8\"";
	if(!@mail($syscfg['blogmail'], $mailtitle, $mailmsg, $mhdr))
		$string .= "<p><font color=\"red\">警告: 送信到 " . $syscfg['blogmail'] . " 失败！</p>\n";

		    $mailtitle = "恭喜您，您已经完成" .$inntype . "申请";
		    $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    您的 $inntype 申请已经审核通过，请您立即登陆我们的网页 $syscfg[url]
核对转信配置文件。并在 cn.bbs.admin.test 组进行转入/转出/跨站砍信测试。祝您
顺利！
    登录链接: $syscfg[url]/loginout.php
--
$syscfg[url]
__EOF__;
		}
		else {
		    $inntype = ($oinnsrv == -1 ? "测试成员" : "正式成员");
			cnweb_log("refused " . (($oinnsrv==-1)?"active":"passive") . " inn request of user {$id}.");
		    $mailtitle = "对不起，您的" .$inntype . "申请失败了";
		    $mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    由于某种原因，您在 $syscfg[url] 的 $inntype 申请失败了，请您
立即登录我们的网页重新申请，并及时联系本站管理员。失败的可能原因包括
	1.您把 cn.bbs.admin.test 同站内测试版混淆了；
	2.您把 cn.bbs.admin.* 的几个组转到一个版了；
	3.您设定的版面与新闻组主题不符；
	4.申请正式成员，却未安装全国十大热门话题；
	5.申请正式成员，却未进行测试成员阶段的测试。
    另外，由于技术问题，fbnt 目前还不能申请正式成员。
    登录链接: $syscfg[url]/loginout.php
--
$syscfg[url]
__EOF__;
		}
		@mail($email, "[cn-bbs] " . $mailtitle, $mailmsg, $mhdr);
	    }
	    else {
		$mailtitle = "您的转信服务器地址变动";
		$mailmsg = <<<__EOF__
尊敬的$name: 您好！
    感谢您使用 CN-BBS.ORG 域名及转信申请系统。

    您的转信服务器地址已经更改，请您立即登录我们的网页 $syscfg[url]
核对转信配置文件。并在 cn.bbs.admin.test 组进行转入/转出/跨站砍信测试。祝您
顺利！
    登录链接: $syscfg[url]/loginout.php
--
$syscfg[url]
__EOF__;
		@mail($email, "[cn-bbs] " . $mailtitle, $mailmsg, $mhdr);
	    }
	}
    }
    $db->close();
}

function acct_modify() {
    global $string, $PHP_SELF;

    $string .= <<<__EOF__
	<form action="$PHP_SELF" method="post">
	输入用户名: <input type="text" name="id" size=="20"> 
	<input type="hidden" name="do" value="check_dns">
	<input type="submit" value="确定">
	</form>
__EOF__;
}

function acct_purge() {
    global $string, $PHP_SELF, $syscfg;
    $day = &cgi_var('day');
    if (!isset($dat)) {
	$string .= <<<__EOF__
	<form action="$PHP_SELF" method="post">
	清除超过 <input type="text" name="day" size=="10"> 天没有激活的帐号！
	<input type="hidden" name="do" value="check_dns">
	<input type="submit" value="确定">
	</form>
__EOF__;
    }
    else {
	require("../db_mysql.php");

	$expire = time() - ($day * 86400);
	$active = _ACCT_ACTIVE_;

//	$db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();
	$db->query("DELETE FROM _my_dns WHERE (authtime < $expire) AND !(xmode & $active)");
	cnweb_log("removed expired({$day} days) user.");
	$string .= "<p>成功除去 " . $db->affected_rows() . "条过期 (" . $day . ")　没激活的帐号！</p>";
	$db->close();
    }
}

function post_mails(){//add by everlove<levels@shuoshuo.net>
	global $syscfg,$string,$PHP_SELF;

	require("../db_mysql.php");
//	$db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();
	$db->query("SELECT email FROM _my_dns");
	$string .= <<<__EOF__
<form action="$PHP_SELF" method="post">
标题: <input type="text" name="subject" size=="10"> <br>
收件人: <input type="text" name="to"><br>
暗送:<br>
__EOF__;
	$i=0;
	while($tmp= $db->fetch_array()){
		$string.="<input type=checkbox name='emails[" . $tmp['id'] . "]' value='".$tmp['email']."'>".$tmp['bbsname'];
		if($i%5==0)$string.="<br>";
		$i++;
	}
	$string.="内容<br></textarea name=\"message\">\n<input type=\"hidden\" name=\"do\" value=\"sendmail\">  <input type=\"submit\" value=\"确定\"></form>";
}

function sendmail(){
	global $syscfg,$string,$PHP_SELF;

	$To=&cgi_var('to');
	$emails=&cgi_var('emails');
	settype($emails,"array");
	$Bcc=implode(",",$emails);
	$Subject=&cgi_var('subject');
	$Body=&cgi_var('message');
	$Mail = new Email;
	//$Mail->setEncoding("base64"); //quoted-printable base64
	$Mail->setCharset("gb2312");
	$Mail->setFrom("noreply@cn-bbs.org");
	$Mail->setTo($To);
	$Mail->setBCC($Bcc);
	$Mail->setSubject($Subject);
	$Mail->setText($Body);
	$Mail->send();
	$string.="信件已经发送";
	cnweb_log("send email to {$To}.");
}

function check_ainn() {
    global $syscfg, $string;

    require("../db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();
    $db->query("SELECT id, name, host, bbsname, bbsid, bbsport FROM _my_dns WHERE innsrv = -1");
    $total = $db->nf();
    $string .= "<ul type=\"square\">\n";
    while ($tmp = $db->fetch_array()) {
	$string .= "<li><a href=\"" . $PHP_SELF . "?do=check_dns&id=" . $tmp['id'] . "\">" 
	. $tmp['name'] . "." . $syscfg['dn'] . "</a> &lt;=&gt; <a href=\"telnet:" 
	. $tmp['host'] . ":" . $tmp['bbsport'] . "\">" .  $tmp['host'] . "</a>( 站名: " 
	. $tmp['bbsname'] . " | 申请人: " . $tmp['bbsid'] . " )</li>\n";
    }
    $string .= "</ul>\n<p> 目前共有 " . $total . " 个BBS申请主动式转信！\n";
    $db->close();
}

function check_pinn() {
    global $syscfg, $string;

    require("../db_mysql.php");
//    $db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
    $db->connect();
    $db->query("SELECT id, name, host, bbsname, bbsid, bbsport FROM _my_dns WHERE innsrv = -2");
    $total = $db->nf();
    $string .= "<ul type=\"square\">\n";
    while ($tmp = $db->fetch_array()) {
	$string .= "<li><a href=\"" . $PHP_SELF . "?do=check_dns&id=" . $tmp['id'] . "\">" 
	. $tmp['name'] . "." . $syscfg['dn'] . "</a> &lt;=&gt; <a href=\"telnet:" 
	. $tmp['host'] . ":" . $tmp['bbsport'] . "\">" .  $tmp['host'] . "</a>( 站名: " 
	. $tmp['bbsname'] . " | 申请人: " . $tmp['bbsid'] . " )</li>\n";
    }
    $string .= "</ul>\n<p> 目前共有 " . $total . " 个BBS申请被动式转信！\n";
    $db->close();
}

function listbbs() {
	global $syscfg, $string;

	require("../db_mysql.php");
	
	$valid = _HOST_ACTIVE_;
	$active = _ACCT_ACTIVE_;
	$start = &cgi_var('start');
	$limit = 20;
	
	if (!isset($start) || $start < 0) $start = 0;
	
	//$db = db_mysql::connect($syscfg['mysql']);
	$db = new db_mysql($syscfg['mysql']);
	$db->connect();
	$db->query("SELECT id, name, host, bbsname, bbsport, bbsdept, innsrv FROM _my_dns WHERE (xmode & $valid) AND (xmode & $active) LIMIT $start, $limit");
	
	$tmp_str = "<form action=\"../query.php\" style=\"margin: 0px\" target=\"_blank\">";
	if ($start != 0)
		$tmp_str .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?start=" . ($start - $limit) . "\">&lt;&lt; 上" . $limit . "个</a>\n";
	
	if ($db->nf() == $limit) 
		$tmp_str .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?start=" . ($start + $limit) . "\">下" . $limit . "个 &gt;&gt;</a>\n";
	
	$tmp_str .= "&nbsp;&nbsp;输入用户名: <input type=\"text\" name=\"id\" size=\"16\" maxlength=\"20\"> (如: SMTH)\n"
	."<input type=\"submit\" value=\"Query\" style=\"font-size: 10px\"></form>\n";
	
	$string .= <<<__EOF__
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
		<td><a href="../query.php?id=$tmp[id]" target="_blank">$tmp[bbsname]</a></td>
		<td><a href="telnet:$tmp[host]:$tmp[bbsport]">$tmp[name].$syscfg[dn]</a></td>
		<td>$tmp[bbsport]</td>
		<td>$tmp[bbsdept]</td>
		<td>$flag</td>
		</tr>
	
__EOF__;
	}
	
	$string .= "\t</table></div>\n<center>" . $tmp_str . "</center>\n";
	
	$db->close();
}

function check_innreq() {
	global $syscfg, $string;

	require("../db_mysql.php");
	$db = new db_mysql($syscfg['mysql']);
	$db->connect();
	
	// 批准申请
	$agree = 0;
	$agree = &cgi_var('agree');
	if($agree > 0) {
		$db->query_first("SELECT username,newinnhost,newinnport,newgroups FROM _inn_req WHERE id={$agree}");
		if($db->f("username")) {
			$username = $db->f("username");
			$newinnhost = $db->f("newinnhost");
			$newinnport = $db->f("newinnport");
			$newgroups = addslashes($db->f("newgroups"));
			$db->query("UPDATE _my_dns SET innhost='{$newinnhost}',innport={$newinnport},groups='{$newgroups}' WHERE name='{$username}'");
			$db->query("UPDATE _inn_req SET agree=1 WHERE id={$agree}");
			cnweb_log("applied user inn modification, record {$agree}.");
		}
	}
	
	// 拒绝申请
	$disagree = 0;
	$disagree = &cgi_var('disagree');
	if($disagree > 0) {
		$db->query("DELETE FROM _inn_req WHERE id={$disagree}");
		cnweb_log("canceled user inn modification, record {$disagree}.");
	}
	
	$db2 = new db_mysql($syscfg['mysql']);
	$db2->connect();
	$db->query("SELECT _inn_req.id,username,newinnhost,newinnport,newgroups,reqtime,innhost,innport,groups FROM _inn_req,_my_dns WHERE agree=0 AND _inn_req.username=_my_dns.name ORDER BY id ASC");
	$db2->query("SELECT * FROM _news_grp WHERE type!=2 ORDER BY type");
	
	while($tmp = $db->fetch_array()) {
		$string .= display_inn_req($tmp, $db2);
		$string .= "<p><div align=\"center\"><a href=\"index.php?do=check_innreq&agree={$tmp["id"]}\">批准这个申请</a> <a href=\"index.php?do=check_innreq&disagree={$tmp["id"]}\">拒绝这个申请</a></div>";
	}
	
	$db2->close();
	$db->close();
}

function cnweb_log($content) {
	global $syscfg;
	$fp = fopen($syscfg['logfile'], "a");
	if($fp == NULL)
		return;
	fwrite($fp, date("Y-m-d H:i:s") . " {$PHP_AUTH_USER} " . $content . "\n");
	fclose($fp);
}

?>
