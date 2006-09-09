<div style="margin:20px">
<h3>cn.bbs.* newsgroups</h3>
<h5>updated on <?php print date('r'); ?></h5>
<pre>
<?php
	require("dbconf.php");
	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$query = "SELECT `name`,`title` FROM `_news_grp` WHERE `name` LIKE '%cn.bbs%' ORDER BY `name` ASC";
	$result = mysql_query($query);
	while($record = mysql_fetch_array($result)) {
		print($record["name"] . "\t" . $record["title"] . "\n");
	}
	mysql_close();
?>
</pre>
</div>
