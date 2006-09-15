<?php
	require("dbconf.php");
	header("Content-type: text/xml");
	$str = "";
	$lastBuildDate = gmdate("D, d M Y H:i:s") . " GMT";
	$str .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
	$str .="<rss version=\"2.0\">\n";
	$str .="<channel>\n";
	$str .="<title>cn.bbs.* 新闻</title>\n";
	$str .="<description>www.cn-bbs.org 网站首页新闻</description>\n";
	$str .="<link>http://www.cn-bbs.org/index.php?c#Mainpage_News</link>\n";
	$str .="<language>zh-cn</language>\n";
	$str .="<generator>cnweb rss</generator>\n";
	$str .="<lastBuildDate>{$lastBuildDate}</lastBuildDate>\n";

	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$result = mysql_query("SELECT *,UNIX_TIMESTAMP(posttime) 'ts_posttime' FROM _mainpage_news ORDER BY id DESC LIMIT 0,5");
	while($record = mysql_fetch_array($result)) {
		$pubDate = gmdate("D, d M Y H:i:s", $record["ts_posttime"]) . " GMT";
		$str .= "<item>\n";
		$str .= "<title>" . $record["title"] . "</title>\n";
		$str .= "<link>http://www.cn-bbs.org/viewnews.php?id=" . $record["id"] . "</link>\n";
		$str .= "<author>cn.bbs.* Administrative Group</author>\n";
		$str .= "<pubDate>{$pubDate}</pubDate>\n";
		$str .= "<guid>http://www.cn-bbs.org/viewnews.php?id=" . $record["id"] . "</guid>\n";
		$str .= "<description>\n<![CDATA[" . $record["content"] . "]]>\n</description>\n";
		$str .= "</item>\n";
	}
	mysql_close();
	
	$str .="</channel>\n";
	$str .="</rss>";
	print($str);
?>
