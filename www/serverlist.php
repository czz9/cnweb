<div style="margin:20px">
<h3>cn.bbs.* Server List</h3>
<h4>updated at <?php print date('y-m-d h:i:s'); ?></h4>
<pre>
<?php
	require("dbconf.php");
	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$query = "SELECT `name`,`host` FROM `_news_srv` ORDER BY `id` ASC";
	$result = mysql_query($query);
	while($record = mysql_fetch_array($result)) {
		print($record["name"] . "\t" . $record["host"] . "\n");
	}
	mysql_close();
?>
</pre>
</div>