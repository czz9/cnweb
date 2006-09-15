<?php
	require("config.php");
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="cn.bbs.* newsgroups administration" />
<meta name="keywords" content="cn.bbs.*,china,chinese,bbs,usenet,newsgroups" />
<meta name="robots" content="index,follow" />
<meta name="copyright" content="Copyright 2001-2006. cn.bbs.* Administrative Group. All Rights Reserved." />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="http://www.cn-bbs.org/rss.php" />
<title>cn.bbs.* newsgroups administration</title>
<link href="default.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="float:right;padding-top:30px;padding-right:5px">
<?php if($chinese) { ?><a href="index.php?e">English Version</a><?php } else { ?><a href="index.php?c">中文版</a><?php } ?></div><div id="divLogo"><a href="index.php"><img src="logo.gif" width="369" height="50" border="0" alt="cn.bbs.*"/></a></div>
<div id="divMenu">
	<br />
<?php
	if($chinese) {
?>
	<div class="menutitle"><a href="index.php?c">首页</a></div>
	<ul>
		<li><a href="index.php?e">English Version</a></li>
	</ul>
	<div class="menutitle"><a href="index.php?c#Documentation_and_FAQs">文档</a></div>
	<ul>
		<li><a href="index.php?c&amp;file=charter">管理章程</a></li>
		<li><a href="index.php?c&amp;file=howto">技术手册</a></li>
		<li><a href="index.php?c&amp;file=manual">版主手册</a></li>
		<li><a href="index.php?c&amp;file=newgroup">开组须知</a></li>
		<li><a href="index.php?c&amp;file=faq">FAQ for news admin</a></li>
	</ul>
	<div class="menutitle"><a href="index.php?c#Resources">资源</a></div>
	<ul>
		<li><a href="index.php?c&amp;display=serverlist">服务器列表</a></li>
		<li><a href="index.php?c&amp;display=newsgroup">新闻组列表</a></li>
		<li><a href="http://webnews.cn-bbs.org" target="_blank">新闻组网页版</a></li>
	</ul>
	<div class="menutitle"><a href="https://panel.cn-bbs.org/">控制面板</a></div>
	<div align="center">
		<form action="https://panel.cn-bbs.org/loginout.php" method="post" id="frmPanel">
			登录名称：<input type="text" name="name" maxlength="16" class="loginbox" /><br />
			登录密码：<input type="password" name="password" maxlength="40" class="loginbox" /><br />
			<div style="margin-top:10px"><input type="submit" value="登录" class="loginbtn" />
			<input type="button" value="注册" class="loginbtn" onclick="location.href='https://panel.cn-bbs.org/register.php';" />
			<input type="button" value="取回密码" class="loginbtn" onclick="location.href='https://panel.cn-bbs.org/lostpw.php';" /></div>
		</form>
	</div>
<?php
	}
	else {
?>
	<div class="menutitle"><a href="index.php?e">Home</a></div>
	<ul>
		<li><a href="index.php?c">中文版</a></li>
	</ul>
	<div class="menutitle"><a href="index.php?e#Documentation_and_FAQs">Doc</a></div>
	<ul>
		<li><a href="index.php?e&amp;file=faq">FAQ</a></li>
		<li><a href="index.php?e&amp;display=newsgroup">Checkgroups</a></li>
		<li><a href="index.php?e&amp;file=pubkey">Pubkey</a></li>
	</ul>
	<div class="menutitle"><a href="https://panel.cn-bbs.org/">Login</a></div>
	<div align="center">
		<form action="https://panel.cn-bbs.org/loginout.php" method="post" id="frmPanel">
			Username：<input type="text" name="name" maxlength="16" class="loginbox" /><br />
			Password：<input type="password" name="password" maxlength="40" class="loginbox" /><br />
			<div style="margin-top:10px"><input type="submit" value="Login" class="loginbtn" />
			<input type="button" value="Register" class="loginbtn" onclick="location.href='https://panel.cn-bbs.org/register.php';" />
			<input type="button" value="Forget Password?" class="loginbtn" onclick="location.href='https://panel.cn-bbs.org/lostpw.php';" /></div>
		</form>
	</div>
<?php
	}
?>
</div>
<div id="divMain">
