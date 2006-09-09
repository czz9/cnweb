<div style="margin:20px">
<h3>服务器列表</h3>
<h5>updated on <?php print date('r'); ?></h5>
<?php
	require("dbconf.php");
	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$query = "SELECT `name`,`url`,`host`,`comment` FROM `_news_srv` ORDER BY `name` ASC";
	$result = mysql_query($query);
?>
<table border="1">
<tr align="center"><th>服务器域名</th><th>FQDN/IP</th><th>服务对象</th></tr>
<?php
	while($record = mysql_fetch_array($result)) {
		print("<tr><td><a href=" . $record["url"] . ">" . $record["name"] . "</a></td><td>" . $record["host"] . "</td><td>" . $record["comment"] . "</td></tr>\n");
	}
?>
</table>
<?php
	mysql_close();
?>
</div>
