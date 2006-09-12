<?php
	require("dbconf.php");
	header("Content-type: text/xml");
	$str = "";
	$lastBuildDate = gmdate("D, d M Y H:i:s") . " GMT";
	$str .= "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
	$str .="\t<rss version=\"2.0\">\n";
	$str .="\t\t<channel>\n";
	$str .="\t\t\t<title>cn.bbs.* 新闻</title>\n";
	$str .="\t\t\t<description>www.cn-bbs.org 网站首页新闻</description>";
	$str .="\t\t\t<link>http://www.cn-bbs.org/index.php?c#Mainpage_News</link>\n";
	$str .="\t\t\t<language>zh-cn</language>\n";
	$str .="\t\t\t<generator>cnweb rss</generator>\n";
	$str .="\t\t\t<lastBuildDate>{$lastBuildDate}</lastBuildDate>\n";

	mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
	mysql_select_db(MYSQL_DB);
	$result = mysql_query("SELECT *,UNIX_TIMESTAMP(posttime) 'ts_posttime' FROM _mainpage_news ORDER BY id DESC LIMIT 0,5");
	while($record = mysql_fetch_array($result)) {
		$pubDate = gmdate("D, d M Y H:i:s", $record["ts_posttime"]) . " GMT";
		$str .= "\t\t\t<item>\n";
		$str .= "\t\t\t\t<title>" . $record["title"] . "</title>\n";
		$str .= "\t\t\t\t<link>http://www.cn-bbs.org/viewnews.php?id=" . $record["id"] . "</link>\n";
		$str .= "\t\t\t\t<author>cn.bbs.* Administrative Group</author>\n";
		$str .= "\t\t\t\t<pubDate>{$pubDate}</pubDate>\n";
		$str .= "\t\t\t\t<guid>http://www.cn-bbs.org/viewnews.php?id=" . $record["id"] . "</guid>\n";
		$str .= "\t\t\t\t<description><![CDATA[" . $record["content"] . "]]></description>\n";
		$str .= "\t\t\t</item>\n";
	}
	mysql_close();
	
	$str .="\t\t</channel>\n";
	$str .="</rss>";
	print($str);
?>
