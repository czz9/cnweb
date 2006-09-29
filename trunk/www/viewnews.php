<?php
	require("config.php");
	require("dbconf.php");
	$pagetitle = "新闻";
	require("header.php");
	
	if(isset($_GET["id"]))
		$id = $_GET["id"];
	else
		exit();
	
	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$result = mysql_query("SELECT * FROM _mainpage_news WHERE id=" . $id);
	$record = mysql_fetch_array($result);
?>
<div id="divMain">
<div style="margin:20px">
<?php
	if($record) {
		print <<<___EOF___
<h3>{$record["title"]}</h3>
<hr />
<pre>
{$record["content"]}
</pre>
<div align="right">发布时间：{$record["posttime"]} <a href="index.php?c">返回首页</a></div>

___EOF___;
	}
	else
		print("没有您要查看的新闻。");
?>
</div>
</div>
<?php
	mysql_close();
	require("footer.php");
?>