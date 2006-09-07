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
				require("doc/CHARTER");
				break;
			case "howto":
				require("doc/HOWTO");
				break;
			case "manual":
				require("doc/MANUAL");
				break;
			case "newgroup":
				require("doc/NEWGROUP");
				break;
			case "faq":
				require("doc/FAQ");
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