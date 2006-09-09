<?php
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
		if(isset($_GET["c"]))
			require("chimain.html");
		else
			require("engmain.html");
	}
	require("footer.php");
?>
