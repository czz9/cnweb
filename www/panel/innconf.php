<?php
// $Id$

include ("config.php");

if (!is_login()) {
    header("Location: loginout.php");
    exit();
}

$name = &my_session_get('dns_name');
$pass = &my_session_get('dns_pass');

require ("db_mysql.php");
// Create Mysql Class
//$db = db_mysql::connect($syscfg['mysql']);
$db = new db_mysql($syscfg['mysql']);
$db->connect();

$db->query("SELECT id, name FROM _news_grp");
$groups = array();
while($db->next_record()) {
    $tmp_id = $db->f('id');
    $tmp_name = $db->f('name');
    $groups[$tmp_id] = $tmp_name;
}

$tmp = $db->query_first("SELECT M.name, M.xmode, M.bbsname, M.innhost, M.innport, M.groups, N.host as host FROM _my_dns M, _news_srv N WHERE N.id = M.innsrv AND M.name = '$name' AND M.pass = '$pass' AND M.groups != '' LIMIT 1");
$db->close();

if (!is_array($tmp))
    $string = "找不到您的记录，您没有登录或者没有加入转信或者管理员尚未审核？请<a href=\"loginout.php\">重新登录</a>！\n";
else {
    list($innhost, $innport) = explode(":", $tmp['host'], 2);
    if (!$innport) $innport  = 119;
    if ($tmp['xmode'] & _INN_PASSIVE_) {
	$innmode = "被动式";
	$xmode = "IHAVE";
    }
    else {
	$innmode = "主动式";
	$xmode = "POST";
    }

    $tmp['groups'] = unserialize($tmp['groups']);
    $newsfeeds = "";
    settype($tmp['groups'], "array");
    asort($tmp['groups']);
    reset($tmp['groups']);
    foreach($tmp['groups'] as $key => $value) {
	if(($groups[$key] == "cn.bbs.admin.announce")||($groups[$key] == "cn.bbs.admin.lists")||($groups[$key] == "cn.bbs.admin.lists.weather")||($groups[$key] == "cn.bbs.campus.tsinghua.news"))
		$newsfeeds .= $groups[$key] . "\t\t" . $value . "\t\tnull\n";
	else
		$newsfeeds .= $groups[$key] . "\t\t" . $value . "\t\tcnnews\n";
    }

    $string = <<<__EOF__
注意: 目前您使用的是 $innmode 转信模式，我们建议您尽快升级到被动模式。
修改配置后请执行~/innd/ctlinnbbsd reload更新。
以下是 innbbsd 参考配置，具体情况可能略有不同，请仔细阅读 <a href=http://www.cn-bbs.org/index.php?#Documentation_and_FAQs target=_blank>F.A.Q.</a> 文件获得解决。
# ------------------------------------------------------------------
# 1. ~/innd/bbsname.bbs
# ------------------------------------------------------------------
$tmp[name]

# ------------------------------------------------------------------
# 2. ~/innd/nodelist.bbs
# 若使用 YTHT 代码，请将 cnnews 行第二项改为对应 IP
# ------------------------------------------------------------------
$tmp[name]	$tmp[innhost]	IHAVE($tmp[innport])	$tmp[bbsname]
cnnews	$tmp[host]	$xmode($innport)		cn.bbs.*

# ------------------------------------------------------------------
# 3. ~/innd/newsfeeds.bbs
# ------------------------------------------------------------------
$newsfeeds

# ------------------------------------------------------------------
# 以上配置是根据您所输入的数据自动生成，仅供参考。
# ------------------------------------------------------------------
__EOF__;
}

$action = "innconf";
include ("header.php");
print "<pre>" . $string . "</pre>\n";
include ("footer.php");
exit();
?>
