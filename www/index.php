<?php
	require("dbconf.php");
	require("header.php");
	if(isset($_GET["display"])) {
		switch($_GET["display"]) {
			case "newsgroup":
				require(defined("STATIC_PAGE")?"newsgroup.html":"newsgroup.php");
				break;
			case "serverlist":
				require(defined("STATIC_PAGE")?"serverlist.html":"serverlist.php");
				break;
			default:
		}
	}
	else if(isset($_GET["file"])) {
?>
<div style="margin:20px"><pre>
<?php
		switch($_GET["file"]) {
			case "charter":
				require("cndoc/CHARTER");
				break;
			case "howto":
				require("cndoc/HOWTO");
				break;
			case "manual":
				require("cndoc/MANUAL");
				break;
			case "newgroup":
				require("cndoc/NEWGROUP");
				break;
			case "faq":
				require("cndoc/FAQ");
				break;
			case "pubkey":
				require("pubkey-cn.bbs.admin.announce");
				break;
			default:
		}
?>
</pre></div>
<?php
	}
	else {
		if($chinese) {
			mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD);
			mysql_select_db(MYSQL_DB);
			$result = mysql_query("SELECT *,UNIX_TIMESTAMP(posttime) 'ts_posttime' FROM _mainpage_news ORDER BY id DESC LIMIT 0,5");
			$mainpage_news_content = "<UL>\n";
			while($record = mysql_fetch_array($result)) {
				$mainpage_news_content .= "  <LI><a href=\"viewnews.php?id={$record["id"]}\">{$record["title"]}</a>";
				$mainpage_news_content .= " (" . date("Y-m-d", $record["ts_posttime"]) . ")</LI>\n";
			}
			$mainpage_news_content .= "</UL>";
			mysql_close();
			require("chimain.html");
		}
		else
			require("engmain.html");
	}
	require("footer.php");
?>
