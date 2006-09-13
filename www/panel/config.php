<?php
// CN-BBS.ORG free domain register system.
// write by <hightman.bbs@bbs.yi.org> IN php. for czz@smth
// $Id$

define ("_HOST_ACTIVE_",	0x001);
define ("_HAVE_WILDCARD_",	0x002);
define ("_INN_PASSIVE_",	0x004);	// 被动转信
define ("_INN_ACTIVE_",		0x008);	// 主动转信
define ("_ACCT_ACTIVE_",	0x010); // 自已激活

$syscfg['title']	= "cn.bbs.* 控制面板";
$syscfg['badid']	= Array("shit", "fuck", "tmd",
				"ns1", "ns",
				"ns2",
				"ns3",
				"mail",
				"bbs",
				"king",
				"nimitz",
				"sysgp",
				"fyf",
				"earth",
				"sysop",
				"7net",
				"amoonearth",
				"cgi",
				"forum",
				"ftp",
				"jessie",
				"leoboard",
				"maple",
				"news",
				"top",
				"wd",
				"www",
				"my",
				"bt",
				"admin", "campus", "comp", "lang", "literal",
				"rec", "sci", "soc", "sports", "talk",
				"blog", "wiki"
				);
$syscfg['mysql']	= Array(
				"Host" => "localhost",	// server host
				"User" => "cnbbs",	// 用户
				"Password" => "password", //密码
				"Database" => "cnbbs"	// 库名
				);
$syscfg['email']	= "noreply@cn-bbs.org";	// admin email.
$syscfg['blogmail']	= "";	// blog email.
$syscfg['url']		= "http://cn-bbs.org/cnweb";
$syscfg['dn']		= "cn-bbs.org";

//////////////////////////////////////////////////////////////////////
//// 以下是管理用到的配置
//////////////////////////////////////////////////////////////////////
$syscfg['admin']	= Array("czz"=>"password");
$syscfg['fpath']	= "/web/tmp/cn-bbs.org.tmp";		// 临时文件的位置, chmod a+rw
$syscfg['dpath']	= "/named/etc/namedb/cn-bbs.org";	// 正式文件的位置, chmod a+r
$syscfg['ipath']	= "/web/tmp/cn-bbs.org.orig";		// 输入文件, named 的头啊
$syscfg['rprog']	= "/web/tmp/update_dns";		// 可执行文件。(update_dns.c)
$syscfg['uid']		= 0;			// 执行系统命令的 uid  [update_dns.c]
$syscfg['gid']		= 0;			// 同上
$syscfg['restart']	= 0;			// 同上，是否重启 named.
$syscfg['mpath']	= "/opt/news/bin/inews -h";	// mail post程序 的位置.
$syscfg['newsgrp']	= "cn.bbs.admin";	// 记录发送到的组。
$syscfg['faqfile']	= "./FAQ";		// FAQ文件

/*
From: xxx@xxx.xxx (xxx)
Newsgroups: cn.bbs.admin
Subject: xxx申请

xxxxxx
*/

session_set_cookie_params(3600);
session_start();

function &my_session_get($name) {
    if ($name == '')
	return NULL;
    if (isset($_SESSION[$name]))
	$ret = &$_SESSION[$name];
    else
	$ret = &my_session_set($name);
    return ($ret);
}

function &my_session_set($name, $value = '') {
    if ($name == '')
	return NULL;
    
    $_SESSION[$name] = $value;
    $ret = &$_SESSION[$name];
    return ($ret);
}

function my_session_unset($name) {
    if (isset($GLOBALS[$name]))
	unset($GLOBALS[$name]);
    unset($_SESSION[$name]);
}

function is_ip($str) {
    return (preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/is", $str));
}

function is_dn($str) {
    return (preg_match("/^([0-9a-z-]{2,}\.){1,}[a-z]{2,4}$/is", $str));
}

function is_host($str) {
    return (is_dn($str) || is_ip($str));
}

function is_valid($name) {
    return (preg_match("/^[0-9a-z][0-9a-z-]{0,10}[0-9a-z]$/is", $name));
}

function is_email($email) {
    return (preg_match("/^[0-9a-z_-]{2,15}@([0-9a-z-]+\.)+[a-z]{2,3}$/is", $email));
}


function &cgi_var($name) {
    $ret = NULL;

    if (isset($_FILES[$name]))	/* 优先 */
	$ret = &$_FILES[$name];
    elseif (isset($_REQUEST[$name]))
	$ret = &$_REQUEST[$name];

    return ($ret);
}

function &pre_var($name) {
    $ret = NULL;

    if (isset($_SERVER[$name]))
	$ret = &$_SERVER[$name];
    elseif (isset($_ENV[$name]))
	$ret = &$_ENV[$name];

    return ($ret);
}

function is_login() {
    if (my_session_get('dns_name') && my_session_get('dns_pass'))
	return 1;
    else
	return 0;
}

?>
