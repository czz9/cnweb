<?php
// help
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id: help.php,v 1.1.1.1 2002/08/21 07:42:45 czz Exp $

include("config.php");

$action = "help";
include("header.php");
$str1 = <<<__EOF__
<table border="0" width="85%" align="center" bgcolor="#efefef">
<td colspan="2">
__EOF__;
print $str1;
$fcontents = file($syscfg['faqfile']);
while (list ($line_num, $line) = each ($fcontents)) {
    echo "", htmlspecialchars ($line), "<br>\n";
}
$str2 = "</td></table>";
print $str2;
include("footer.php");
?>
