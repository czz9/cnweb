<?php
	require("dbconf.php");
	if(isset($_GET["c"]))
		$chinese = true;
	else if(isset($_GET["e"]))
		$chinese = false;
	else {
		if(substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 3) == "zh-")
			$chinese = true;
		else
			$chinese = false;
	}
	if(isset($_GET["display"])) {
		$isfile = false;
		switch($_GET["display"]) {
			case "newsgroup":
				$contentfile = defined("STATIC_PAGE")?"newsgroup.html":"newsgroup.php";
				$pagetitle = "新闻组列表";
				break;
			case "serverlist":
				$contentfile = defined("STATIC_PAGE")?"serverlist.html":"serverlist.php";
				$pagetitle = "服务器列表";
				break;
			default:
		}
	}
	else if(isset($_GET["file"])) {
		$isfile = true;
		switch($_GET["file"]) {
			case "charter":
				$contentfile = "cndoc/CHARTER";
				$pagetitle = "管理章程";
				break;
			case "howto":
				$contentfile = "cndoc/HOWTO";
				$pagetitle = "技术手册";
				break;
			case "manual":
				$contentfile = "cndoc/MANUAL";
				$pagetitle = "版主手册";
				break;
			case "newgroup":
				$contentfile = "cndoc/NEWGROUP";
				$pagetitle = "开组须知";
				break;
			case "faq":
				$contentfile = "cndoc/FAQ";
				$pagetitle = "FAQ";
				break;
			case "pubkey":
				$contentfile = "pubkey-cn.bbs.admin.announce";
				$pagetitle = "PGP公钥";
				break;
			default:
		}
	}
	else {
		$isfile = false;
		mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
		mysql_select_db(MYSQL_DB);
		if($chinese) {
			$result = mysql_query("SELECT *,UNIX_TIMESTAMP(posttime) 'ts_posttime' FROM _mainpage_news ORDER BY id DESC LIMIT 0,5");
			$mainpage_news_content = "<ul>\n";
			while($record = mysql_fetch_array($result)) {
				$mainpage_news_content .= "<li><a href=\"viewnews.php?id={$record["id"]}\">{$record["title"]}</a>";
				$mainpage_news_content .= " (" . date("Y-m-d", $record["ts_posttime"]) . ")</li>\n";
			}
			$mainpage_news_content .= "</ul>";
			$contentfile = "chimain.html";
		}
		else {
			$result = mysql_query("SELECT * FROM _news_srv WHERE status=1 ORDER BY name ASC");
			$server_list = "<ul>\n";
			while($record = mysql_fetch_array($result)) {
				$server_list .= "<li><a href=\"http://{$record["name"]}/\" target=\"_blank\">{$record["name"]}</a>";
				$server_list .= " ({$record["comment_en"]})</li>\n";
			}
			$server_list .= "</ul>";
			$contentfile = "engmain.html";
		}
		mysql_close();
	}
	require("header.php");
	print("<div id=\"divMain\">");
	if($isfile)
		print("<div style=\"margin:20px\"><pre>");
	require($contentfile);
	if($isfile)
		prnt("</pre></div>");
	print("</div>");
	require("footer.php");
?>
