<?php

function display_inn_req($record, $db) {
	$string = "";
	$string .= "<p>申请单序号：" . $record["id"] . "<br />";
	$string .= "申请人账号：" . $record["username"] . "<br />";
	if($record["innhost"] != $record["newinnhost"])
		$string .= "BBS站域名变更： " . $record["innhost"] . " => " . $record["newinnhost"] . "<br />";
	if($record["innport"] != $record["newinnport"])
		$string .= "innbbsd端口变更：" . $record["innport"] . " => " . $record["newinnport"] . "<br />";
	$oldgroup = unserialize($record["groups"]);
	$newgroup = unserialize($record["newgroups"]);
	$db->seek(0);
	while($ngi = $db->fetch_array()) {
		if(isset($oldgroup[$ngi["id"]])) {
			if(isset($newgroup[$ngi["id"]])) {
				if($oldgroup[$ngi["id"]] != $newgroup[$ngi["id"]]) 
					$string .= "转信版面变更： " . $oldgroup[$ngi["id"]] . " => " . $newgroup[$ngi["id"]] . " (" . $ngi["name"] . "/" . $ngi["title"] . ")<br />";
			}
			else {
				$string .= "取消新闻组： " . $ngi["name"] . "/" . $ngi["title"] . "<br />";
			}
		}
		else {
			if(isset($newgroup[$ngi["id"]])) {
				$string .= "增加新闻组： " . $ngi["name"] . "/" . $ngi["title"] . " 转信到 " . $newgroup[$ngi["id"]] . " 版面<br />";
			}
		}
	}
	$string .= "提交时间：" . $record["reqtime"] . "<br />";
	$string .= "</p>";
	return $string;
}

?>