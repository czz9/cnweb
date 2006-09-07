<?php
//admin.php
//hightman@hightman.net
//$Id: admin.php,v 1.3 2003/12/06 11:51:30 czz Exp $
include("config.php");

$PHP_AUTH_USER = &pre_var('PHP_AUTH_USER');
$PHP_AUTH_PW = &pre_var('PHP_AUTH_PW');

//un authenticate
if (!isset($syscfg['admin'][$PHP_AUTH_USER]) || ($syscfg['admin'][$PHP_AUTH_USER] != $PHP_AUTH_PW)) {
    header("WWW-Authenticate: Basic realm=\"" . $syscfg['dn'] . " 管理入口\""); 
    header("Status: 401 Unauthorized");
    header("HTTP/1.0 401 Unauthorized");
    echo "<h4>403: Forbidden</h4>\n";
    echo "Access deined!  Please contact with <i>Administrator</i> or <a href=\"list.php\">back</a>.\n";
    exit();
}

$action = "admin";
$does = array(	"gen_innd" => "news配置",
		"gen_named" => "named配置",
		"reload_named" => "named更新",
		"acct_modify" => "账号管理",
		"acct_purge" => "账号清除",
		"post_mails" => "信件通知",
		"check_ainn" => "主动转信审核",
		"check_pinn" => "被动转信审核",
		"check_dns" => "资料审核");
$string = "<div align=\"center\"> | ";

foreach($does as $key => $value)
    $string .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?do=" . $key . "\">" . $value . "</a> |\n";

$string .= "<hr size=\"1\" noshade></div>\n";

$do = &cgi_var('do');
if (!isset($do)) $do = "adm_default";

include ("admin_func.php");
if (!function_exists($do))
    die ("Error![" . $do . "]");
else
    $do();

include("header.php");
print $string;
include("footer.php");
?>
