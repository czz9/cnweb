<?php
//$Id$
include("../config.php");

$PHP_AUTH_USER = &pre_var('PHP_AUTH_USER');
$PHP_AUTH_PW = &pre_var('PHP_AUTH_PW');

//un authenticate
if (!isset($syscfg['admin'][$PHP_AUTH_USER]) || ($syscfg['admin'][$PHP_AUTH_USER] != $PHP_AUTH_PW)) {
    header("WWW-Authenticate: Basic realm=\"" . $syscfg['dn'] . " Admin Center\""); 
    header("Status: 401 Unauthorized");
    header("HTTP/1.0 401 Unauthorized");
    echo "<h4>403: Forbidden</h4>\n";
    echo "Access deined!  Please contact with <i>Administrator</i> or <a href=\"../stat.php\">back</a>.\n";
    exit();
}

$action = "admin";
$does = array(
		"listnews" => "服务器列表",
		"listgroups" => "新闻组列表",
		"gen_innd" => "news配置",
		"gen_named" => "named配置",
		"reload_named" => "named更新",
		"listbbs" => "BBS列表",
		"acct_modify" => "账号管理",
		"acct_purge" => "账号清除",
		"post_mails" => "信件通知",
		"check_ainn" => "主动转信审核",
		"check_pinn" => "被动转信审核",
		"check_dns" => "资料审核",
		"check_innreq" => "变更审核");
		
$do = &cgi_var('do');
if (!isset($do)) $do = "adm_default";

$string .= "管理功能::" . $does[$do];

$string .= "<hr size=\"1\" noshade>\n";

include ("admin_func.php");
if (!function_exists($do))
    die ("Error![" . $do . "]");
else
    $do();

include("../header.php");
print $string;
include("../footer.php");
?>
