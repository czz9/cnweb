<?php
// newsgroup list
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
		$grouplist .= "<a href=query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a> ";
	    } else
		$grouplist .= "<I><a href=query.php?id=" . $db2->f('id') . " target=\"_blank\">" . $db2->f('name') . "</a></I> ";
	}
	$grouplist .= "(" . $sum_passive . "/" . $sum . ")";
	$grouplist .= "</td></tr>\n";
        $db2->close();
    }
    $db1->close();

    $grouplist .= "</table>\n";

    $string = <<<__EOF__
     <table border="0" width="100%" align="center" bgcolor="#efefef">
     <tr>
     <td>
	$grouplist
     </td>
     </tr>
     </table>
__EOF__;

$action = "listgroup";
include ("header.php");
print $string;
include ("footer.php");
exit();

?>
