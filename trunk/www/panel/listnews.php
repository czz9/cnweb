<?php
// news server list
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id$

include ("config.php");
require ("db_mysql.php");

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
	        $newslist .= "<a href=query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a> ";
	    } else
	        $newslist .= "<I><a href=query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a></I> ";
	}
	$newslist .= "(" . $sum_passive . "/" . $sum . ")";
	$newslist .= "</td><td>" . $db1->f('comment') . "</td></tr>\n";
        $db2->close();
    }
    $db1->close();

    $newslist .= "</table>\n";

    $string = <<<__EOF__
     <table border="0" width="100%" align="center" bgcolor="#efefef">
     <tr>
     <td>
	$newslist
     </td>
     </tr>
     </table>
__EOF__;

$action = "listnews";
include ("header.php");
print $string;
include ("footer.php");
exit();

?>
