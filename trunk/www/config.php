<?php
	define("STATIC_PAGE", 1);
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
?>