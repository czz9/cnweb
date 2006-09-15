<div style="margin:20px">
<h3>服务器列表</h3>
<h5>updated on <?php print date('r'); ?></h5>
<?php
	require("dbconf.php");
	
	$ststr[0] = "<span style=\"color:#0000FF\">未知</span>";
	$ststr[1] = "<span style=\"color:#009900\">正常</span>";
	$ststr[2] = "<span style=\"color:#FF0000\">故障</span>";
	$ststr[3] = "<span style=\"color:#999900\">暂停</span>";
	
	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$query = "SELECT * FROM `_news_srv` ORDER BY `name` ASC";
	$result = mysql_query($query);
?>
<table border="1">
<tr align="center">
<th>服务器域名</th>
<th>状态</th>
<th>FQDN/IP</th>
<th>服务对象</th>
</tr>
<?php
	while($record = mysql_fetch_array($result)) {
		print("<tr>\n<td><a href=\"" . $record["url"] . "\" target=\"_blank\">" . $record["name"] . "</a></td>\n<td>" . $ststr[$record["status"]] . "</td>\n<td>" . $record["host"] . "</td>\n<td>" . $record["comment"] . "</td>\n</tr>\n");
	}
?>
</table>
<?php
	mysql_close();
?>
</div>
